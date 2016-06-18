package com.bpho.bpho_music;

import android.app.ProgressDialog;
import android.content.Intent;
import android.content.res.Configuration;
import android.net.Uri;
import android.os.StrictMode;
import android.support.v4.widget.DrawerLayout;
import android.support.v7.app.ActionBarDrawerToggle;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.Button;
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

public class ModifProfil extends AppCompatActivity {

    //session
    private SessionManager session;

    //menu
    private ListView mDrawerList;
    private DrawerLayout mDrawerLayout;
    private ArrayAdapter<String> mAdapter;
    private ActionBarDrawerToggle mDrawerToggle;
    private String mActivityTitle;

    int idUser;

    TextView TVname, TVfirstName, TVmail, TVnewPassword, TVpasswordConfirm, TVcurrentPassword, TVphone;
    Button btnApply;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.content_modif_profil);
        if (android.os.Build.VERSION.SDK_INT > 9) {
            StrictMode.ThreadPolicy policy = new StrictMode.ThreadPolicy.Builder().permitAll().build();
            StrictMode.setThreadPolicy(policy);
        }

        session = new SessionManager(getApplicationContext());

        //menu
        mDrawerList = (ListView)findViewById(R.id.amisList);mDrawerLayout = (DrawerLayout)findViewById(R.id.drawer_layout);
        mActivityTitle = getTitle().toString();

        addDrawerItems();
        setupDrawer();

        getSupportActionBar().setDisplayHomeAsUpEnabled(true);
        getSupportActionBar().setHomeButtonEnabled(true);

        TVname = (TextView) findViewById(R.id.name);
        TVfirstName = (TextView) findViewById(R.id.firstName);
        TVmail = (TextView) findViewById(R.id.email);
        TVnewPassword = (TextView) findViewById(R.id.password);
        TVpasswordConfirm = (TextView) findViewById(R.id.passwordConfirm);
        TVphone = (TextView) findViewById(R.id.phone);
        TVcurrentPassword = (TextView) findViewById(R.id.currentPassword);
        btnApply = (Button) findViewById(R.id.btnApply);

        TVfirstName.setText(session.getFirstName());
        TVname.setText(session.getLastName());
        TVmail.setText(session.getEmail());
        TVphone.setText(session.getTel());

        btnApply.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                modifProfil();
            }
        });

    }

    public void modifProfil(){
        if(TVnewPassword.getText().toString().trim().length() < 8 && !TVnewPassword.getText().toString().trim().equals("")) {
            Toast.makeText(this, getString(R.string.passwordTooShort), Toast.LENGTH_SHORT).show();
        } else if(!TVnewPassword.getText().toString().trim().equals(TVpasswordConfirm.getText().toString().trim())) {
            Toast.makeText(this, getString(R.string.passwordDontMatch), Toast.LENGTH_SHORT).show();
        } else if(TVcurrentPassword.getText().toString().trim().equals("")) {
            Toast.makeText(this, getString(R.string.emptyCurrentPassword), Toast.LENGTH_SHORT).show();
        } else {
            try {
                HttpClient httpclient = new DefaultHttpClient();
                HttpPost httppost = new HttpPost("http://91.121.151.137/TFE/php/modifUser.php");
                ArrayList<NameValuePair> nameValuePairs = new ArrayList<NameValuePair>(7);
                nameValuePairs.add(new BasicNameValuePair("id", session.getId()));
                nameValuePairs.add(new BasicNameValuePair("name", TVname.getText().toString().trim()));
                nameValuePairs.add(new BasicNameValuePair("firstName", TVfirstName.getText().toString().trim()));
                nameValuePairs.add(new BasicNameValuePair("oldMail", session.getEmail()));
                nameValuePairs.add(new BasicNameValuePair("mail", TVmail.getText().toString().trim()));
                nameValuePairs.add(new BasicNameValuePair("phone", TVphone.getText().toString().trim()));
                nameValuePairs.add(new BasicNameValuePair("newPassword", TVnewPassword.getText().toString().trim()));
                nameValuePairs.add(new BasicNameValuePair("oldPassword", TVcurrentPassword.getText().toString().trim()));
                httppost.setEntity(new UrlEncodedFormEntity(nameValuePairs));
                ResponseHandler<String> responseHandler = new BasicResponseHandler();
                final String response = httpclient.execute(httppost, responseHandler);

                JSONObject jsonObject = new JSONObject(response);
                String error = jsonObject.getString("error");

                switch (error) {
                    case "ok":
                        Toast.makeText(this, getString(R.string.profilUpdated), Toast.LENGTH_SHORT).show();
                        session.setLastName(jsonObject.getString("name"));
                        session.setFirstName(jsonObject.getString("firstName"));
                        session.setEmail(TVmail.getText().toString().trim());
                        session.setTel(TVphone.getText().toString().trim());
                        Intent intent = new Intent(ModifProfil.this, Profil.class);
                        intent.putExtra("idUser", session.getId());
                        startActivity(intent);
                        break;
                    case "wrong user":
                        Toast.makeText(this, getString(R.string.wrongCurrentPassword), Toast.LENGTH_SHORT).show();
                        break;
                    case "wrong email":
                        Toast.makeText(this, getString(R.string.incorrectMail), Toast.LENGTH_SHORT).show();
                        break;
                    case "email taken":
                        Toast.makeText(this, getString(R.string.emailTaken), Toast.LENGTH_SHORT).show();
                        break;
                }

            } catch (Exception e) {
                e.printStackTrace();
            }
        }
    }

    //menu
    private void addDrawerItems() {
        final String[] osArray = new String[] {"Agenda", "Liste des utilisateurs", "Messagerie", "Profil", "Se déconnecter"};

        mAdapter = new ArrayAdapter<String>(this, android.R.layout.simple_list_item_1, osArray);
        mDrawerList.setAdapter(mAdapter);

        mDrawerList.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
                if (position == 0) {
                    Intent intent = new Intent(ModifProfil.this, Agenda.class);
                    startActivity(intent);
                }
                if (position == 1) {
                    //Intent intent = new Intent(ModifProfil.this, ListeUsers.class);
                    //startActivity(intent);
                }
                if (position == 2) {
                    //Intent intent = new Intent(ModifProfil.this, Messagerie.class);
                    //startActivity(intent);
                }
                if (position == 3) {
                    Intent intent = new Intent(ModifProfil.this, Profil.class);
                    intent.putExtra("idUser", session.getId());
                    startActivity(intent);
                }
                if (position == 4) {
                    logoutUser();
                }
            }
        });
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
        Intent intent = new Intent(ModifProfil.this, Login.class);
        startActivity(intent);
        finish();
    }

    private void setupDrawer() {
        mDrawerToggle = new ActionBarDrawerToggle(this, mDrawerLayout, R.string.drawer_open, R.string.drawer_close) {

            /** Called when a drawer has settled in a completely open state. */
            public void onDrawerOpened(View drawerView) {
                super.onDrawerOpened(drawerView);
                getSupportActionBar().setTitle("Navigation!");
                invalidateOptionsMenu(); // creates call to onPrepareOptionsMenu()
            }

            /** Called when a drawer has settled in a completely closed state. */
            public void onDrawerClosed(View view) {
                super.onDrawerClosed(view);
                getSupportActionBar().setTitle(mActivityTitle);
                invalidateOptionsMenu(); // creates call to onPrepareOptionsMenu()
            }
        };

        mDrawerToggle.setDrawerIndicatorEnabled(true);
        mDrawerLayout.setDrawerListener(mDrawerToggle);
    }

    @Override
    protected void onPostCreate(Bundle savedInstanceState) {
        super.onPostCreate(savedInstanceState);
        // Sync the toggle state after onRestoreInstanceState has occurred.
        mDrawerToggle.syncState();
    }

    @Override
    public void onConfigurationChanged(Configuration newConfig) {
        super.onConfigurationChanged(newConfig);
        mDrawerToggle.onConfigurationChanged(newConfig);
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        // Inflate the menu; this adds items to the action bar if it is present.
        //getMenuInflater().inflate(R.menu.menu_main, menu);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        // Handle action bar item clicks here. The action bar will
        // automatically handle clicks on the Home/Up button, so long
        // as you specify a parent activity in AndroidManifest.xml.
        int id = item.getItemId();

        // Activate the navigation drawer toggle
        if (mDrawerToggle.onOptionsItemSelected(item)) {
            return true;
        }

        return super.onOptionsItemSelected(item);
    }
}
