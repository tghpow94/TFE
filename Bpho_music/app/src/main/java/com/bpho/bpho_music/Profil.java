package com.bpho.bpho_music;

import android.app.Activity;
import android.app.AlertDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.res.Configuration;
import android.net.ConnectivityManager;
import android.os.StrictMode;
import android.support.design.widget.FloatingActionButton;
import android.support.v4.widget.DrawerLayout;
import android.support.v7.app.ActionBarDrawerToggle;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.MenuItem;
import android.view.View;
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
import org.apache.http.conn.ClientConnectionManager;
import org.apache.http.conn.HttpClientConnectionManager;
import org.apache.http.impl.client.BasicResponseHandler;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.message.BasicNameValuePair;
import org.json.JSONArray;
import org.json.JSONObject;
import org.w3c.dom.Text;

import java.util.ArrayList;

public class Profil extends AppCompatActivity {

    private SessionManager session;

    //menu
    private ListView mDrawerList;
    private DrawerLayout mDrawerLayout;
    private ArrayAdapter<String> mAdapter;
    private ActionBarDrawerToggle mDrawerToggle;
    private String mActivityTitle;

    //profil
    public int idUser;
    public String name, firstName, mail, dateRegister, dateLastConnect, phone, droit;
    public String instruments;
    public boolean hasInstrument;

    TextView TVname, TVmail, TVphone, TVdroit, TVinstruments;

    //image
    ImageLoader imageLoader = AppController.getInstance().getImageLoader();
    NetworkImageView thumbNail;
    String thumbnailUrl;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.content_profil);
        getSupportActionBar().setTitle(getString(R.string.profil));
        if (android.os.Build.VERSION.SDK_INT > 9) {
            StrictMode.ThreadPolicy policy = new StrictMode.ThreadPolicy.Builder().permitAll().build();
            StrictMode.setThreadPolicy(policy);
        }


        session = new SessionManager(getApplicationContext());

        mDrawerList = (ListView)findViewById(R.id.menu);
        mDrawerLayout = (DrawerLayout)findViewById(R.id.drawer_layout);
        mActivityTitle = getTitle().toString();

        getSupportActionBar().setDisplayHomeAsUpEnabled(true);
        getSupportActionBar().setHomeButtonEnabled(true);

        TVname = (TextView) findViewById(R.id.name);
        TVmail = (TextView) findViewById(R.id.mail);
        TVphone = (TextView) findViewById(R.id.phone);
        TVdroit = (TextView) findViewById(R.id.droit);
        TVinstruments = (TextView) findViewById(R.id.instruments);

        thumbNail = (NetworkImageView) findViewById(R.id.imageUser);

        Intent intent = getIntent();
        idUser = intent.getIntExtra("idUser", Integer.valueOf(session.getId()));

        addDrawerItems();
        setupDrawer();

        if(getUser()) {
            TVname.setText(firstName + " " + name);
            TVmail.setText(mail);
            if (phone != "null")
                TVphone.setText(phone);
            else
                TVphone.setText("");
            TVdroit.setText(droit);
            TVinstruments.setText(instruments);

            if (imageLoader == null)
                imageLoader = AppController.getInstance().getImageLoader();
            thumbNail.setImageUrl(thumbnailUrl, imageLoader);
        }

        FloatingActionButton fab = (FloatingActionButton) findViewById(R.id.fab);
        if(idUser == Integer.valueOf(session.getId())) {
            fab.setVisibility(View.VISIBLE);
            fab.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    Intent intent = new Intent(Profil.this, ModifProfil.class);
                    startActivity(intent);
                    finish();
                }
            });
        } else {
            fab.setVisibility(View.INVISIBLE);
        }
    }

    private boolean checkInternet() {
        ConnectivityManager con=(ConnectivityManager)getSystemService(Profil.CONNECTIVITY_SERVICE);
        boolean wifi=con.getNetworkInfo(ConnectivityManager.TYPE_WIFI).isConnectedOrConnecting();
        boolean internet=con.getNetworkInfo(ConnectivityManager.TYPE_MOBILE).isConnectedOrConnecting();
        //check Internet connection
        if(internet||wifi)
        {
            return true;
        }else{
            new AlertDialog.Builder(this)
                    .setIcon(R.drawable.logo)
                    .setTitle("No internet connection")
                    .setMessage("Please turn on mobile data")
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

    private boolean getUser() {
        if(checkInternet()) {
            try {
                HttpClient httpclient = new DefaultHttpClient();
                HttpPost httppost = new HttpPost("http://91.121.151.137/TFE/php/getUser.php");
                ArrayList<NameValuePair> nameValuePairs = new ArrayList<NameValuePair>(1);
                nameValuePairs.add(new BasicNameValuePair("id", String.valueOf(idUser)));
                httppost.setEntity(new UrlEncodedFormEntity(nameValuePairs));
                ResponseHandler<String> responseHandler = new BasicResponseHandler();
                final String response = httpclient.execute(httppost, responseHandler);

                JSONObject jsonObject = new JSONObject(response);
                Boolean error = jsonObject.getBoolean("error");

                if (!error) {
                    name = jsonObject.getString("name");
                    firstName = jsonObject.getString("firstName");
                    mail = jsonObject.getString("email");
                    dateRegister = jsonObject.getString("dateRegister");
                    dateLastConnect = jsonObject.getString("dateLastConnect");
                    phone = jsonObject.getString("phone");
                    droit = jsonObject.getString("droit");
                    thumbnailUrl = "http://91.121.151.137/TFE/images/u" + idUser + ".jpg";
                    hasInstrument = jsonObject.getBoolean("hasInstrument");
                    instruments = "";
                    if (hasInstrument) {
                        JSONArray jsonArray = jsonObject.getJSONArray("instruments");
                        for (int i = 0; i < jsonArray.length(); i++) {
                            String instru = jsonArray.get(i).toString();
                            if (i > 0) {
                                instruments += ", ";
                            }
                            instruments += instru;
                        }
                    }
                    return true;
                }

            } catch (Exception e) {
                e.printStackTrace();
                Toast.makeText(this, getString(R.string.noInternet), Toast.LENGTH_SHORT).show();
                return false;
            }
        }
        return false;
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
        Intent intent = new Intent(Profil.this, Login.class);
        startActivity(intent);
        finish();
    }

    private void addDrawerItems() {
        final String[] osArray;
        if(idUser == Integer.valueOf(session.getId())) {
            osArray = new String[]{getString(R.string.agenda), getString(R.string.listUsers), getString(R.string.messagerie), getString(R.string.logout)};
        } else {
            osArray = new String[]{getString(R.string.agenda), getString(R.string.listUsers), getString(R.string.messagerie), getString(R.string.profil), getString(R.string.logout)};
        }

        mAdapter = new ArrayAdapter<String>(this, android.R.layout.simple_list_item_1, osArray);
        mDrawerList.setAdapter(mAdapter);

        mDrawerList.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
                if (position == 0) {
                    Intent intent = new Intent(Profil.this, Agenda.class);
                    startActivity(intent);
                    finish();
                }
                if (position == 1) {
                    Intent intent = new Intent(Profil.this, ListUsers.class);
                    startActivity(intent);
                    finish();
                }
                if (position == 2) {
                    Intent intent = new Intent(Profil.this, Messagerie.class);
                    startActivity(intent);
                    finish();
                }
                if (position == 3) {
                    if (idUser == Integer.valueOf(session.getId())) {
                        logoutUser();
                    } else {
                        Intent intent = new Intent(Profil.this, Profil.class);
                        intent.putExtra("id", Integer.valueOf(session.getId()));
                        startActivity(intent);
                        finish();
                    }
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
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {

        if (mDrawerToggle.onOptionsItemSelected(item)) {
            return true;
        }
        return super.onOptionsItemSelected(item);
    }
}
