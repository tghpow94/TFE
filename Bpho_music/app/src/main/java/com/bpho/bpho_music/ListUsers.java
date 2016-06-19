package com.bpho.bpho_music;

import android.app.AlertDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.res.Configuration;
import android.net.ConnectivityManager;
import android.os.StrictMode;
import android.support.v4.widget.DrawerLayout;
import android.support.v7.app.ActionBarDrawerToggle;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.text.Html;
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

import com.android.volley.toolbox.ImageLoader;
import com.android.volley.toolbox.NetworkImageView;

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

public class ListUsers extends AppCompatActivity {

    private SessionManager session;

    //menu
    private ListView mDrawerList;
    private DrawerLayout mDrawerLayout;
    private ArrayAdapter<String> mAdapter;
    private ActionBarDrawerToggle mDrawerToggle;
    private String mActivityTitle;

    ListView LVusers;
    private ArrayList<String> list = new ArrayList<String>();
    private ArrayAdapter<String> activiteAdapter;
    LayoutInflater mInflater;

    ImageLoader imageLoader = AppController.getInstance().getImageLoader();

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.content_list_users);
        getSupportActionBar().setTitle(getString(R.string.listUsers));
        if (android.os.Build.VERSION.SDK_INT > 9) {
            StrictMode.ThreadPolicy policy = new StrictMode.ThreadPolicy.Builder().permitAll().build();
            StrictMode.setThreadPolicy(policy);
        }
        mInflater = (LayoutInflater) ListUsers.this.getSystemService(Context.LAYOUT_INFLATER_SERVICE);

        session = new SessionManager(getApplicationContext());

        mDrawerList = (ListView)findViewById(R.id.menu);
        mDrawerLayout = (DrawerLayout)findViewById(R.id.drawer_layout);
        mActivityTitle = getTitle().toString();
        addDrawerItems();
        setupDrawer();

        getSupportActionBar().setDisplayHomeAsUpEnabled(true);
        getSupportActionBar().setHomeButtonEnabled(true);

        LVusers = (ListView) findViewById(R.id.LVusers);

        getUsers();
        addOptionOnClick(list);
    }

    private boolean checkInternet() {
        ConnectivityManager con=(ConnectivityManager)getSystemService(ListUsers.CONNECTIVITY_SERVICE);
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

    private void getUsers() {
        if(checkInternet()) {
            try {
                HttpClient httpclient = new DefaultHttpClient();
                HttpPost httppost = new HttpPost("http://91.121.151.137/TFE/php/getUsers.php");
                ArrayList<NameValuePair> nameValuePairs = new ArrayList<NameValuePair>(1);
                nameValuePairs.add(new BasicNameValuePair("idUser", session.getId()));
                httppost.setEntity(new UrlEncodedFormEntity(nameValuePairs));
                ResponseHandler<String> responseHandler = new BasicResponseHandler();
                final String response = httpclient.execute(httppost, responseHandler);

                final JSONArray jsonArray = new JSONArray(response);
                JSONObject jObj1 = jsonArray.getJSONObject(0);
                Boolean error = jObj1.getBoolean("error");

                if (!error) {
                    for (int i = 0; i < jsonArray.length(); i++) {
                        JSONObject jObj = jsonArray.getJSONObject(i);
                        String name = jObj.getString("name");
                        String firstName = jObj.getString("firstName");
                        String idUser = jObj.getString("id");
                        String urlImage = "http://91.121.151.137/TFE/images/u" + idUser + ".jpg";
                        String user = idUser + "<spanseparate>" + firstName + " " + name + "<spanseparate>" + urlImage;
                        list.add(user);
                    }

                    if (jsonArray.length() == 0) {
                        String alerte = getString(R.string.noUser);
                        list.add(alerte);
                    }

                    activiteAdapter = new ArrayAdapter<String>(getApplicationContext(), R.layout.layout_listusers, list) {
                        @Override
                        public View getView(int position, View convertView, ViewGroup parent) {
                            View row;

                            if (null == convertView) {
                                row = mInflater.inflate(R.layout.layout_listusers, null);
                            } else {
                                row = convertView;
                            }


                            if (imageLoader == null)
                                imageLoader = AppController.getInstance().getImageLoader();
                            NetworkImageView thumbNail = (NetworkImageView) row.findViewById(R.id.imageUser);
                            TextView tv1 = (TextView) row.findViewById(R.id.text1);
                            if (jsonArray.length() > 0) {
                                String alerteTemp = getItem(position);
                                String[] alerte = alerteTemp.split("<spanseparate>");
                                tv1.setText(alerte[1]);
                                if (thumbNail != null) {
                                    thumbNail.setImageUrl(alerte[2], imageLoader);
                                }
                            } else {
                                tv1.setText(Html.fromHtml(getItem(position)));
                            }

                            return row;
                        }
                    };

                    LVusers.setAdapter(activiteAdapter);

                } else {
                    Toast.makeText(this, getString(R.string.noMoreUser), Toast.LENGTH_SHORT).show();
                }

            } catch (Exception e) {
                e.printStackTrace();
                Toast.makeText(this, getString(R.string.noInternet), Toast.LENGTH_SHORT).show();
            }
        }
    }

    private void addOptionOnClick(final ArrayList<String> list) {
        LVusers.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> parent, View view, final int position, long id) {
                afficherProfil(position, list);
            }
        });
    }

    public void afficherProfil(int position, ArrayList<String> list){
        String[] userTab = list.get(position).split("<spanseparate>");
        Intent intent = new Intent(ListUsers.this, Profil.class);
        intent.putExtra("idUser", Integer.valueOf(userTab[0]));
        startActivity(intent);
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
        Intent intent = new Intent(ListUsers.this, Login.class);
        startActivity(intent);
        finish();
    }

    private void addDrawerItems() {
        final String[] osArray;
        osArray = new String[] {getString(R.string.agenda), getString(R.string.messagerie), getString(R.string.profil), getString(R.string.logout)};

        mAdapter = new ArrayAdapter<String>(this, android.R.layout.simple_list_item_1, osArray);
        mDrawerList.setAdapter(mAdapter);

        mDrawerList.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
                if (position == 0) {
                    Intent intent = new Intent(ListUsers.this, Agenda.class);
                    startActivity(intent);
                }
                if (position == 1) {
                    Intent intent = new Intent(ListUsers.this, Messagerie.class);
                    startActivity(intent);
                }
                if (position == 2) {
                    Intent intent = new Intent(ListUsers.this, Profil.class);
                    intent.putExtra("idUser", Integer.valueOf(session.getId()));
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

        if (mDrawerToggle.onOptionsItemSelected(item)) {
            return true;
        }
        return super.onOptionsItemSelected(item);
    }
}
