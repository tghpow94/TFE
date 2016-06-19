package com.bpho.bpho_music;

import android.app.AlertDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.res.Configuration;
import android.net.ConnectivityManager;
import android.os.Handler;
import android.os.StrictMode;
import android.support.v4.widget.DrawerLayout;
import android.support.v7.app.ActionBarDrawerToggle;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.text.Html;
import android.view.LayoutInflater;
import android.view.MenuItem;
import android.view.View;
import android.view.ViewGroup;
import android.view.inputmethod.InputMethodManager;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.EditText;
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
import java.util.concurrent.RunnableFuture;

public class Messagerie extends AppCompatActivity {

    private SessionManager session;

    private boolean isRunningForeground;
    Handler handler;
    Runnable runnable;
    int i = 0;

    //menu
    private ListView mDrawerList;
    private DrawerLayout mDrawerLayout;
    private ArrayAdapter<String> mAdapter;
    private ActionBarDrawerToggle mDrawerToggle;
    private String mActivityTitle;

    EditText ETmessage;
    Button btnSend;

    int nbMessages = 0;
    int nbMessages2 = 0;

    ListView LVmessages;
    private ArrayList<String> list = new ArrayList<String>();
    private ArrayAdapter<String> activiteAdapter;
    LayoutInflater mInflater;

    ImageLoader imageLoader = AppController.getInstance().getImageLoader();

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.content_messagerie);
        getSupportActionBar().setTitle(getString(R.string.messagerie));
        if (android.os.Build.VERSION.SDK_INT > 9) {
            StrictMode.ThreadPolicy policy = new StrictMode.ThreadPolicy.Builder().permitAll().build();
            StrictMode.setThreadPolicy(policy);
        }

        mInflater = (LayoutInflater) Messagerie.this.getSystemService(Context.LAYOUT_INFLATER_SERVICE);

        session = new SessionManager(getApplicationContext());

        mDrawerList = (ListView)findViewById(R.id.menu);
        mDrawerLayout = (DrawerLayout)findViewById(R.id.drawer_layout);
        mActivityTitle = getTitle().toString();
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);
        getSupportActionBar().setHomeButtonEnabled(true);
        addDrawerItems();
        setupDrawer();

        ETmessage = (EditText) findViewById(R.id.ETmessage);
        btnSend = (Button) findViewById(R.id.btnSend);
        LVmessages = (ListView) findViewById(R.id.LVmessages);

        btnSend.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                sendMessage();
            }
        });

        getMessages();
        removeKeyboard();

        handler = new Handler();
        runnable = new Runnable() {

            @Override
            public void run() {
                nbMessages2 = countMessages();
                if (nbMessages != nbMessages2) {
                    getMessages();
                    activiteAdapter.notifyDataSetChanged();
                    nbMessages = nbMessages2;
                }
                handler.postDelayed( this, 5 * 1000 );
            }
        };

        handler.postDelayed(runnable, 5 * 1000 );
        removeKeyboard();
    }

    @Override
    protected void onPause() {
        super.onPause();
        isRunningForeground = false;
    }

    @Override
    protected void onResume() {
        super.onResume();
        isRunningForeground = true;
    }


    private boolean checkInternet() {
        ConnectivityManager con=(ConnectivityManager)getSystemService(Messagerie.CONNECTIVITY_SERVICE);
        boolean wifi=con.getNetworkInfo(ConnectivityManager.TYPE_WIFI).isConnectedOrConnecting();
        boolean internet=con.getNetworkInfo(ConnectivityManager.TYPE_MOBILE).isConnectedOrConnecting();
        //check Internet connection
        if(internet||wifi)
        {
            return true;
        }else{
            if(isRunningForeground && i == 0) {
                i++;
                new AlertDialog.Builder(this)
                        .setIcon(R.drawable.logo)
                        .setTitle(getString(R.string.noInternet))
                        .setMessage(getString(R.string.internetRequest))
                        .setPositiveButton("OK", new DialogInterface.OnClickListener() {
                            @Override
                            public void onClick(DialogInterface dialog, int which) {
                                //code for exit
                                i = 0;
                                Intent intent = new Intent(Intent.ACTION_MAIN);
                                intent.addCategory(Intent.CATEGORY_HOME);
                                intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
                                startActivity(intent);
                            }

                        })
                        .show();
            }

            return false;
        }
    }

    private int countMessages() {
        if(checkInternet()) {
            try {
                HttpClient httpclient = new DefaultHttpClient();
                HttpPost httppost = new HttpPost("http://91.121.151.137/TFE/php/countMessages.php");
                ArrayList<NameValuePair> nameValuePairs = new ArrayList<NameValuePair>(1);
                nameValuePairs.add(new BasicNameValuePair("id", session.getId()));
                httppost.setEntity(new UrlEncodedFormEntity(nameValuePairs));
                ResponseHandler<String> responseHandler = new BasicResponseHandler();
                final String response = httpclient.execute(httppost, responseHandler);

                final JSONObject jsonObject = new JSONObject(response);

                return jsonObject.getInt("nb");

            } catch (Exception e) {
                e.printStackTrace();
                Toast.makeText(this, getString(R.string.noInternet), Toast.LENGTH_SHORT).show();
            }
        }
        return 0;
    }

    private void removeKeyboard() {
        InputMethodManager imm = (InputMethodManager)getSystemService(Context.INPUT_METHOD_SERVICE);
        imm.hideSoftInputFromWindow(ETmessage.getWindowToken(), 0);
    }

    private void getMessages() {
        if(checkInternet()) {
            list.clear();
            try {
                HttpClient httpclient = new DefaultHttpClient();
                HttpPost httppost = new HttpPost("http://91.121.151.137/TFE/php/getMessages.php");
                ArrayList<NameValuePair> nameValuePairs = new ArrayList<NameValuePair>(1);
                nameValuePairs.add(new BasicNameValuePair("id", session.getId()));
                httppost.setEntity(new UrlEncodedFormEntity(nameValuePairs));
                ResponseHandler<String> responseHandler = new BasicResponseHandler();
                final String response = httpclient.execute(httppost, responseHandler);

                final JSONArray jsonArray = new JSONArray(response);

                if (response.contains("{")) {

                    for (int i = 0; i < jsonArray.length(); i++) {
                        JSONObject jsonObject = jsonArray.getJSONObject(i);
                        String name = jsonObject.getString("name");
                        String firstName = jsonObject.getString("firstName");
                        String message = jsonObject.getString("text");
                        String date = jsonObject.getString("date");

                        String[] dateTabTemp = date.split(" ");
                        String[] dateTemp = dateTabTemp[0].split("\\-");
                        String[] timeTemp = dateTabTemp[1].split("\\:");
                        date = dateTemp[2] + "/" + dateTemp[1] + "/" + dateTemp[0] + " " + timeTemp[0] + "h" + timeTemp[1];

                        String idUser = jsonObject.getString("idUser");
                        String urlImage = "http://91.121.151.137/TFE/images/u" + idUser + ".jpg";
                        String separate = "<spanseparate>";
                        String row = firstName + " " + name + separate + date + separate + message + separate + urlImage;
                        list.add(row);
                    }

                    if (jsonArray.length() == 0) {
                        String row = getString(R.string.noMessage);
                        list.add(row);
                    }

                    activiteAdapter = new ArrayAdapter<String>(getApplicationContext(), R.layout.layout_message, list) {
                        @Override
                        public View getView(int position, View convertView, ViewGroup parent) {
                            View row;
                            int i = 0;

                            if (null == convertView) {
                                row = mInflater.inflate(R.layout.layout_message, null);
                            } else {
                                row = convertView;
                            }


                            if (imageLoader == null)
                                imageLoader = AppController.getInstance().getImageLoader();
                            NetworkImageView thumbNail = (NetworkImageView) row.findViewById(R.id.imageUser);
                            TextView TVname = (TextView) row.findViewById(R.id.TVname);
                            TextView TVdate = (TextView) row.findViewById(R.id.TVdate);
                            TextView TVmessage = (TextView) row.findViewById(R.id.TVmessage);

                            if (jsonArray.length() > 0) {
                                String rowTemp = getItem(position);
                                String[] alerte = rowTemp.split("<spanseparate>");
                                TVname.setText(alerte[0]);
                                TVdate.setText(alerte[1]);
                                TVmessage.setText(alerte[2]);
                                if (thumbNail != null) {
                                    thumbNail.setImageUrl(alerte[3], imageLoader);
                                }
                            } else {
                                TVmessage.setText(getItem(position));
                            }

                            return row;
                        }
                    };

                    LVmessages.setAdapter(activiteAdapter);

                }
            } catch (Exception e) {
                e.printStackTrace();
                Toast.makeText(this, getString(R.string.noInternet), Toast.LENGTH_SHORT).show();
            }
        }
    }

    private void sendMessage() {
        if(checkInternet()) {
            if (ETmessage.getText().toString().trim().equals("")) {
                Toast.makeText(this, getString(R.string.emptyMessage), Toast.LENGTH_SHORT).show();
            } else {
                try {
                    HttpClient httpclient = new DefaultHttpClient();
                    HttpPost httppost = new HttpPost("http://91.121.151.137/TFE/php/sendMessageChat.php");
                    ArrayList<NameValuePair> nameValuePairs = new ArrayList<NameValuePair>(2);
                    nameValuePairs.add(new BasicNameValuePair("idUser", session.getId()));
                    nameValuePairs.add(new BasicNameValuePair("text", ETmessage.getText().toString().trim()));
                    httppost.setEntity(new UrlEncodedFormEntity(nameValuePairs));
                    ResponseHandler<String> responseHandler = new BasicResponseHandler();
                    final String response = httpclient.execute(httppost, responseHandler);

                    Toast.makeText(this, getString(R.string.messageSend), Toast.LENGTH_SHORT).show();

                    removeKeyboard();

                    ETmessage.setText("");

                    getMessages();

                } catch (Exception e) {
                    e.printStackTrace();
                    Toast.makeText(this, getString(R.string.noInternet), Toast.LENGTH_SHORT).show();
                }
            }
        }
        removeKeyboard();
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
        Intent intent = new Intent(Messagerie.this, Login.class);
        startActivity(intent);
        finish();
    }

    private void addDrawerItems() {
        final String[] osArray;
        osArray = new String[]{getString(R.string.agenda), getString(R.string.listUsers), getString(R.string.profil), getString(R.string.logout)};

        mAdapter = new ArrayAdapter<String>(this, android.R.layout.simple_list_item_1, osArray);
        mDrawerList.setAdapter(mAdapter);

        mDrawerList.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
                if (position == 0) {
                    Intent intent = new Intent(Messagerie.this, Agenda.class);
                    startActivity(intent);
                    finish();
                }
                if (position == 1) {
                    Intent intent = new Intent(Messagerie.this, ListUsers.class);
                    startActivity(intent);
                    finish();
                }
                if (position == 2) {
                    Intent intent = new Intent(Messagerie.this, Profil.class);
                    startActivity(intent);
                    finish();
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
    public boolean onOptionsItemSelected(MenuItem item) {

        if (mDrawerToggle.onOptionsItemSelected(item)) {
            return true;
        }
        return super.onOptionsItemSelected(item);
    }
}
