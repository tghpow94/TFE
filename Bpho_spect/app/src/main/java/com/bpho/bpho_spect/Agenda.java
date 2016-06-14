package com.bpho.bpho_spect;

import android.app.ProgressDialog;
import android.content.Intent;
import android.graphics.Color;
import android.graphics.drawable.ColorDrawable;
import android.os.Bundle;
import android.os.StrictMode;
import android.support.design.widget.FloatingActionButton;
import android.support.design.widget.Snackbar;
import android.support.v4.widget.SwipeRefreshLayout;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.Toolbar;
import android.text.TextUtils;
import android.util.Log;
import android.view.View;
import android.view.Menu;
import android.view.MenuItem;
import android.widget.AdapterView;
import android.widget.Button;
import android.widget.ListView;
import android.widget.TextView;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.VolleyLog;
import com.android.volley.toolbox.ImageLoader;
import com.android.volley.toolbox.JsonArrayRequest;
import com.android.volley.toolbox.Volley;

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
import java.util.Date;
import java.util.List;
import java.util.Locale;

public class Agenda extends AppCompatActivity implements SwipeRefreshLayout.OnRefreshListener {
    private static final String TAG = Agenda.class.getSimpleName();

    private static final String url = "http://91.121.151.137/TFE/php/getAllEvents.php";
    private ProgressDialog pDialog;
    private List<Event> eventList = new ArrayList<Event>();
    private ListView listView;
    private CustomListAdapter adapter;
    private SwipeRefreshLayout swipeRefreshLayout;
    private int offSet = 0;
    private String langue = Locale.getDefault().getLanguage();

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.layout_agenda);
        Toolbar toolbar = (Toolbar) findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);
        getSupportActionBar().setTitle(getString(R.string.agenda_title));
        if (android.os.Build.VERSION.SDK_INT > 9) {
            StrictMode.ThreadPolicy policy = new StrictMode.ThreadPolicy.Builder().permitAll().build();
            StrictMode.setThreadPolicy(policy);
        }

        listView = (ListView) findViewById(R.id.list);
        swipeRefreshLayout = (SwipeRefreshLayout) findViewById(R.id.swipe_refresh_layout);
        swipeRefreshLayout.setOnRefreshListener(this);
        adapter = new CustomListAdapter(this, eventList);
        listView.setAdapter(adapter);

        pDialog = new ProgressDialog(this);
        pDialog.setMessage(getString(R.string.loading));
        pDialog.show();

        onRefresh();

        addOptionOnClick(eventList);

        FloatingActionButton fab = (FloatingActionButton) findViewById(R.id.fab);
        fab.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(Agenda.this, Contact.class);
                startActivity(intent);
            }
        });
    }

    @Override
    public void onRefresh() {
        String id = "-1";
        if (offSet > 0) {
            id = eventList.get(0).getId();
        }
        eventList.clear();
        JSONArray response = null;
        try {
            response = new JSONArray(getEvents());
        } catch (Exception e) {
            e.printStackTrace();
        }

        for (int i = 0; i < response.length(); i++) {
            try {
                JSONObject obj = response.getJSONObject(i);
                Event event = new Event(obj);
                eventList.add(event);

            } catch (JSONException e) {
                e.printStackTrace();
            }
        }

        if (id.equals(eventList.get(0).getId()) && offSet < 60) {
            onRefresh();
        } else if (offSet >= 60) {
            Snackbar.make(swipeRefreshLayout,getString(R.string.noMoreEvent), Snackbar.LENGTH_LONG).setAction("Action", null).show();
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
            nameValuePairs.add(new BasicNameValuePair("type", "general"));
            nameValuePairs.add(new BasicNameValuePair("idUser", "0"));
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

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        getMenuInflater().inflate(R.menu.menu_main, menu);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        switch (item.getItemId()) {
            case android.R.id.home:
                onBackPressed();
                break;
            case R.id.action_generalInfos:
                startActivity(new Intent(Agenda.this, InfosGenerales.class));
        }
        return super.onOptionsItemSelected(item);
    }
}
