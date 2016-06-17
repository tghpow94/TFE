package com.bpho.bpho_music;

import android.app.ProgressDialog;
import android.content.Intent;
import android.content.res.Configuration;
import android.net.Uri;
import android.os.StrictMode;
import android.support.v4.widget.DrawerLayout;
import android.support.v4.widget.SwipeRefreshLayout;
import android.support.v7.app.ActionBarDrawerToggle;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.support.v7.widget.Toolbar;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.ListView;
import android.widget.Toast;

import org.apache.http.NameValuePair;
import org.apache.http.client.HttpClient;
import org.apache.http.client.ResponseHandler;
import org.apache.http.client.entity.UrlEncodedFormEntity;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.impl.client.BasicResponseHandler;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.message.BasicNameValuePair;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.List;
import java.util.Locale;

public class Agenda extends AppCompatActivity implements SwipeRefreshLayout.OnRefreshListener {

    private SessionManager session;

    int i;
    //menu
    private ListView mDrawerList;
    private DrawerLayout mDrawerLayout;
    private ArrayAdapter<String> mAdapter;
    private ActionBarDrawerToggle mDrawerToggle;
    private String mActivityTitle;

    //list
    private static final String url = "http://91.121.151.137/TFE/php/getAllEvents.php";
    private ProgressDialog pDialog;
    private List<Event> eventList = new ArrayList<Event>();
    private ListView listView;
    private CustomListAdapter adapter;
    private SwipeRefreshLayout swipeRefreshLayout;
    private int offSet = 0;
    private String langue = Locale.getDefault().getLanguage();
    String type = "general";

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.content_agenda);
        if (android.os.Build.VERSION.SDK_INT > 9) {
            StrictMode.ThreadPolicy policy = new StrictMode.ThreadPolicy.Builder().permitAll().build();
            StrictMode.setThreadPolicy(policy);
        }

        session = new SessionManager(getApplicationContext());

        mDrawerList = (ListView)findViewById(R.id.menu);
        mDrawerLayout = (DrawerLayout)findViewById(R.id.drawer_layout);
        mActivityTitle = getTitle().toString();
        addDrawerItems();
        setupDrawer();

        getSupportActionBar().setDisplayHomeAsUpEnabled(true);
        getSupportActionBar().setHomeButtonEnabled(true);

        listView = (ListView) findViewById(R.id.list);
        swipeRefreshLayout = (SwipeRefreshLayout) findViewById(R.id.swipe_refresh_layout);
        swipeRefreshLayout.setOnRefreshListener(this);
        adapter = new CustomListAdapter(this, eventList);
        listView.setAdapter(adapter);

        pDialog = new ProgressDialog(this);
        pDialog.setMessage(getString(R.string.loading));
        pDialog.show();

        Intent intent = getIntent();
        type = intent.getStringExtra("type");
        if (type == null) {
            type = "general";
        }

        onRefresh();

        addOptionOnClick(eventList);
    }

    @Override
    public void onBackPressed() {
    }

    @Override
    public void onRefresh() {
        String getEventsResponse = "";
        String id = "-1";
        if (offSet > 0 && eventList.size() > 0) {
            id = eventList.get(0).getId();
        }
        eventList.clear();
        JSONArray response = null;
        try {
            getEventsResponse = getEvents();
            if(getEventsResponse.contains("{")) {
                response = new JSONArray(getEventsResponse);
            }
        } catch (Exception e) {
            e.printStackTrace();
        }

        if (response != null) {
            for (int i = 0; i < response.length(); i++) {
                try {
                    JSONObject obj = response.getJSONObject(i);
                    Event event = new Event(obj);
                    eventList.add(event);

                } catch (JSONException e) {
                    e.printStackTrace();
                }
            }
        }

        if ((!getEventsResponse.contains("{") || id.equals(eventList.get(0).getId())) && offSet < 60) {
            onRefresh();
        } else if (offSet >= 60 && getEventsResponse.contains("{")) {
            Toast.makeText(Agenda.this, getString(R.string.noMoreEvent), Toast.LENGTH_LONG).show();
        } else if (offSet >= 60 && !getEventsResponse.contains("{")){
            Toast.makeText(Agenda.this, getString(R.string.noUserEvents), Toast.LENGTH_LONG).show();
        } else {
            adapter.notifyDataSetChanged();
        }
    }

    private void addOptionOnClick(final List<Event> list) {

        listView.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> parent, View view, final int position, long id) {
                afficherEvent(position, list);
            }
        });
    }

    public void afficherEvent(int position, List<Event> list){
        Intent intent = new Intent(this, AfficherEvent.class);
        intent.putExtra("event", list.get(position));
        startActivity(intent);
    }

    @Override
    public void onDestroy() {
        super.onDestroy();
        hidePDialog();
    }

    private void hidePDialog() {
        if (pDialog != null) {
            pDialog.dismiss();
            pDialog = null;
        }
    }

    public String getEvents() {
        swipeRefreshLayout.setRefreshing(true);
        try {
            HttpClient httpclient = new DefaultHttpClient();
            HttpPost httppost = new HttpPost("http://91.121.151.137/TFE/php/getAllEvents.php");
            ArrayList<NameValuePair> nameValuePairs = new ArrayList<NameValuePair>(3);
            nameValuePairs.add(new BasicNameValuePair("offset", String.valueOf(offSet)));
            nameValuePairs.add(new BasicNameValuePair("lang", langue));
            nameValuePairs.add(new BasicNameValuePair("type", type));
            nameValuePairs.add(new BasicNameValuePair("idUser", session.getId()));
            httppost.setEntity(new UrlEncodedFormEntity(nameValuePairs));
            ResponseHandler<String> responseHandler = new BasicResponseHandler();
            final String response = httpclient.execute(httppost, responseHandler);
            hidePDialog();
            offSet = offSet + 6;
            swipeRefreshLayout.setRefreshing(false);
            return response;
        } catch (Exception e) {
            e.printStackTrace();
        }
        hidePDialog();
        return "rate";
    }

    private void logoutUser() {
        session.setLogin(false);
        session.setEmail(null);
        session.setFirstName(null);
        session.setLastName(null);
        session.setId(null);
        session.setInscription(null);
        session.setDroit(null);
        session.setLastConnect(null);
        session.setTel(null);

        // Launching the login activity
        Intent intent = new Intent(Agenda.this, Login.class);
        startActivity(intent);
        finish();
    }

    private void addDrawerItems() {
        final String[] osArray;
        osArray = new String[] {"Liste des utilisateurs", "Messagerie", "Profil", "Se déconnecter"};

        mAdapter = new ArrayAdapter<String>(this, android.R.layout.simple_list_item_1, osArray);
        mDrawerList.setAdapter(mAdapter);

        mDrawerList.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
                if (position == 0) {
                    //Intent intent = new Intent(Agenda.this, ListeUsers.class);
                    //startActivity(intent);
                }
                if (position == 1) {
                    //Intent intent = new Intent(Agenda.this, Messagerie.class);
                    //startActivity(intent);
                }
                if (position == 2) {
                    Intent intent = new Intent(Agenda.this, Profil.class);
                    intent.putExtra("id", Integer.valueOf(session.getId()));
                    startActivity(intent);
                }
                if (position == 3) {
                    logoutUser();
                }
            }
        });
    }

    private void setupDrawer() {
        mDrawerToggle = new ActionBarDrawerToggle(this, mDrawerLayout, R.string.drawer_open, R.string.drawer_close) {

            public void onDrawerOpened(View drawerView) {
                super.onDrawerOpened(drawerView);
                getSupportActionBar().setTitle(getString(R.string.navigation));
                invalidateOptionsMenu();
            }

            public void onDrawerClosed(View view) {
                super.onDrawerClosed(view);
                getSupportActionBar().setTitle(mActivityTitle);
                invalidateOptionsMenu();
            }
        };

        mDrawerToggle.setDrawerIndicatorEnabled(true);
        mDrawerLayout.setDrawerListener(mDrawerToggle);
    }

    @Override
    protected void onPostCreate(Bundle savedInstanceState) {
        super.onPostCreate(savedInstanceState);
        mDrawerToggle.syncState();
    }

    @Override
    public void onConfigurationChanged(Configuration newConfig) {
        super.onConfigurationChanged(newConfig);
        mDrawerToggle.onConfigurationChanged(newConfig);
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        getMenuInflater().inflate(R.menu.menu_agenda, menu);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {

        switch (item.getItemId()) {
            case R.id.events_perso:
                Intent intent = new Intent(Agenda.this, Agenda.class);
                intent.putExtra("type", "perso");
                startActivity(intent);
                break;
            case R.id.events_general:
                Intent intent2 = new Intent(Agenda.this, Agenda.class);
                intent2.putExtra("type", "general");
                startActivity(intent2);
                break;
        }

        if (mDrawerToggle.onOptionsItemSelected(item)) {
            return true;
        }
        return super.onOptionsItemSelected(item);
    }
}
