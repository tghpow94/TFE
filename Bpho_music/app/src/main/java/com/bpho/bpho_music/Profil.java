package com.bpho.bpho_music;

import android.content.Intent;
import android.content.res.Configuration;
import android.os.StrictMode;
import android.support.v4.widget.DrawerLayout;
import android.support.v7.app.ActionBarDrawerToggle;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.MenuItem;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.ListView;

public class Profil extends AppCompatActivity {

    private SessionManager session;

    //menu
    private ListView mDrawerList;
    private DrawerLayout mDrawerLayout;
    private ArrayAdapter<String> mAdapter;
    private ActionBarDrawerToggle mDrawerToggle;
    private String mActivityTitle;

    //profil
    public int id;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.content_profil);
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

        Intent intent = getIntent();
        id = intent.getIntExtra("id", Integer.valueOf(session.getId()));

        addDrawerItems();
        setupDrawer();
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
        if(id == Integer.valueOf(session.getId())) {
            osArray = new String[]{"Agenda", "Liste des utilisateurs", "Messagerie", "Se déconnecter"};
        } else {
            osArray = new String[]{"Agenda", "Liste des utilisateurs", "Messagerie", "Profil", "Se déconnecter"};
        }

        mAdapter = new ArrayAdapter<String>(this, android.R.layout.simple_list_item_1, osArray);
        mDrawerList.setAdapter(mAdapter);

        mDrawerList.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
                if (position == 0) {
                    Intent intent = new Intent(Profil.this, Agenda.class);
                    startActivity(intent);
                }
                if (position == 1) {
                    //Intent intent = new Intent(Profil.this, ListeUsers.class);
                    //startActivity(intent);
                }
                if (position == 2) {
                    //Intent intent = new Intent(Profil.this, Messagerie.class);
                    //startActivity(intent);
                }
                if (position == 3) {
                    if (id == Integer.valueOf(session.getId())) {
                        logoutUser();
                    } else {
                        Intent intent = new Intent(Profil.this, Profil.class);
                        intent.putExtra("id", Integer.valueOf(session.getId()));
                        startActivity(intent);
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
