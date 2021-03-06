package com.bpho.bpho_music;

import android.app.AlertDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.res.Configuration;
import android.net.ConnectivityManager;
import android.os.Bundle;
import android.os.StrictMode;
import android.support.v4.widget.DrawerLayout;
import android.support.v7.app.ActionBarDrawerToggle;
import android.support.v7.app.AppCompatActivity;
import android.text.Html;
import android.text.Spanned;
import android.view.LayoutInflater;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.ListView;
import android.widget.TextView;
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
import org.json.JSONObject;

import java.util.ArrayList;

public class Alerts extends AppCompatActivity {

    private SessionManager session;

    //menu
    //private ListView mDrawerList;
    //private DrawerLayout mDrawerLayout;
    //private ArrayAdapter<String> mAdapter;
    //private ActionBarDrawerToggle mDrawerToggle;
    //private String mActivityTitle;

    int id = 0;
    ListView LValerts;
    private ArrayList<String> list = new ArrayList<String>();
    private ArrayAdapter<String> activiteAdapter;
    LayoutInflater mInflater;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.content_alerts);
        getSupportActionBar().setTitle(getString(R.string.alerts));
        if (android.os.Build.VERSION.SDK_INT > 9) {
            StrictMode.ThreadPolicy policy = new StrictMode.ThreadPolicy.Builder().permitAll().build();
            StrictMode.setThreadPolicy(policy);
        }
        mInflater = (LayoutInflater) Alerts.this.getSystemService(Context.LAYOUT_INFLATER_SERVICE);

        session = new SessionManager(getApplicationContext());

        /*mDrawerList = (ListView)findViewById(R.id.menu);
        mDrawerLayout = (DrawerLayout)findViewById(R.id.drawer_layout);
        mActivityTitle = getTitle().toString();
        addDrawerItems();
        setupDrawer();*/

        getSupportActionBar().setDisplayHomeAsUpEnabled(true);
        //getSupportActionBar().setHomeButtonEnabled(true);

        Intent intent = getIntent();
        id = intent.getIntExtra("idEvent", Integer.valueOf(session.getId()));

        LValerts = (ListView) findViewById(R.id.LValerts);
        getAlerts(id);

    }

    private boolean checkInternet() {
        ConnectivityManager con=(ConnectivityManager)getSystemService(Alerts.CONNECTIVITY_SERVICE);
        boolean wifi=con.getNetworkInfo(ConnectivityManager.TYPE_WIFI).isConnectedOrConnecting();
        boolean internet=con.getNetworkInfo(ConnectivityManager.TYPE_MOBILE).isConnectedOrConnecting();
        //check Internet connection
        if(internet||wifi)
        {
            return true;
        }else{
            new AlertDialog.Builder(this)
                    .setIcon(R.drawable.logo)
                    .setTitle(getString(R.string.noInternet))
                    .setMessage(getString(R.string.internetRequest))
                    .setPositiveButton("OK", new DialogInterface.OnClickListener()
                    {
                        @Override
                        public void onClick(DialogInterface dialog, int which) {
                            //code for exit
                            Intent intent = new Intent(Intent.ACTION_MAIN);
                            intent.addCategory(Intent.CATEGORY_HOME);
                            intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
                            startActivity(intent);
                        }

                    })
                    .show();
            return false;
        }
    }

    public void getAlerts(int id) {
        if(checkInternet()) {
            try {
                HttpClient httpclient = new DefaultHttpClient();
                HttpPost httppost = new HttpPost("http://91.121.151.137/TFE/php/getEventAlerts.php");
                ArrayList<NameValuePair> nameValuePairs = new ArrayList<NameValuePair>(2);
                nameValuePairs.add(new BasicNameValuePair("idUser", session.getId()));
                nameValuePairs.add(new BasicNameValuePair("idEvent", String.valueOf(id)));
                httppost.setEntity(new UrlEncodedFormEntity(nameValuePairs));
                ResponseHandler<String> responseHandler = new BasicResponseHandler();
                final String response = httpclient.execute(httppost, responseHandler);

                final JSONArray jsonArray = new JSONArray(response);

                for (int i = 0; i < jsonArray.length(); i++) {
                    JSONObject jObj = jsonArray.getJSONObject(i);
                    String date = jObj.getString("date");
                    String message = jObj.getString("text");
                    String[] tempTab = date.split(" ");
                    String[] dateTemp = tempTab[0].split("-");
                    String[] timeTemp = tempTab[1].split(":");
                    date = dateTemp[2] + "/" + dateTemp[1] + "/" + dateTemp[0] + " - " + timeTemp[0] + ":" + timeTemp[1];
                    String alerte = "<br><b>" + date + "</b><spanseparate>" + message;
                    list.add(alerte);
                }

                if (jsonArray.length() == 0) {
                    String alerte = getString(R.string.noAlert);
                    list.add(alerte);
                }

                activiteAdapter = new ArrayAdapter<String>(getApplicationContext(), R.layout.spinner_theme2, list) {
                    @Override
                    public View getView(int position, View convertView, ViewGroup parent) {
                        View row;

                        if (null == convertView) {
                            row = mInflater.inflate(R.layout.spinner_theme2, null);
                        } else {
                            row = convertView;
                        }

                        TextView tv1 = (TextView) row.findViewById(R.id.text1);
                        TextView tv2 = (TextView) row.findViewById(R.id.text2);
                        if (jsonArray.length() > 0) {
                            String alerteTemp = getItem(position);
                            String[] alerte = alerteTemp.split("<spanseparate>");
                            tv1.setText(Html.fromHtml(alerte[0]));
                            tv2.setText(Html.fromHtml(alerte[1]));
                        } else {
                            tv2.setText(Html.fromHtml(getItem(position)));
                        }

                        return row;
                    }
                };

                LValerts.setAdapter(activiteAdapter);

            } catch (Exception e) {
                e.printStackTrace();
                Toast.makeText(this, getString(R.string.noInternet), Toast.LENGTH_SHORT).show();
            }
        }
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        //getMenuInflater().inflate(R.menu.menu_events, menu);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        switch (item.getItemId()) {
            case android.R.id.home:
                onBackPressed();
                break;
        }

        /*if (mDrawerToggle.onOptionsItemSelected(item)) {
            return true;
        }*/
        return super.onOptionsItemSelected(item);
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
        Intent intent = new Intent(Alerts.this, Login.class);
        startActivity(intent);
        finish();
    }

    /*private void addDrawerItems() {
        final String[] osArray;
        osArray = new String[] {getString(R.string.event), "Liste des utilisateurs", "Messagerie", "Profil", "Se déconnecter"};

        mAdapter = new ArrayAdapter<String>(this, android.R.layout.simple_list_item_1, osArray);
        mDrawerList.setAdapter(mAdapter);

        mDrawerList.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
                if (position == 0) {
                    onBackPressed();
                }
                if (position == 1) {
                    //Intent intent = new Intent(AfficherEvent.this, ListeUsers.class);
                    //startActivity(intent);
                }
                if (position == 2) {
                    //Intent intent = new Intent(AfficherEvent.this, Messagerie.class);
                    //startActivity(intent);
                }
                if (position == 3) {
                    Intent intent = new Intent(Alerts.this, Profil.class);
                    intent.putExtra("id", Integer.valueOf(session.getId()));
                    startActivity(intent);
                }
                if (position == 4) {
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
    }*/

}
