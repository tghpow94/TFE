package com.bpho.bpho_music;

import android.content.Intent;
import android.content.res.Configuration;
import android.graphics.Paint;
import android.net.Uri;
import android.os.Bundle;
import android.os.StrictMode;
import android.support.v4.widget.DrawerLayout;
import android.support.v7.app.ActionBarDrawerToggle;
import android.support.v7.app.AppCompatActivity;
import android.text.Html;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.ListView;
import android.widget.TextView;

import com.android.volley.toolbox.ImageLoader;
import com.android.volley.toolbox.NetworkImageView;


public class AfficherEvent extends AppCompatActivity {

    private SessionManager session;

    //menu
    private ListView mDrawerList;
    private DrawerLayout mDrawerLayout;
    private ArrayAdapter<String> mAdapter;
    private ActionBarDrawerToggle mDrawerToggle;
    private String mActivityTitle;

    NetworkImageView thumbNail;
    TextView title, date, address, prix, reservation, description;
    ImageLoader imageLoader = AppController.getInstance().getImageLoader();
    String url;
    int eventID = 0;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.content_event);
        getSupportActionBar().setTitle(getString(R.string.event));
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

        thumbNail = (NetworkImageView) findViewById(R.id.thumbnail);
        title = (TextView) findViewById(R.id.title);
        date = (TextView) findViewById(R.id.date);
        address = (TextView) findViewById(R.id.address);
        description = (TextView) findViewById(R.id.description);
        prix = (TextView) findViewById(R.id.prix);
        reservation = (TextView) findViewById(R.id.reservation);
        Event event = (Event)getIntent().getSerializableExtra("event");

        eventID = Integer.valueOf(event.getId());
        imageLoader = AppController.getInstance().getImageLoader();
        thumbNail.setImageUrl(event.getThumbnailUrl(), imageLoader);
        title.setText(event.getTitle());
        date.setText(event.getDate() + " - " + event.getStartTime());
        address.setText(event.getFullAddress());
        prix.setText(event.getPrice());
        description.setText(Html.fromHtml(event.getDescription()));
        url = event.getReservationLink();
        reservation.setPaintFlags(Paint.UNDERLINE_TEXT_FLAG);

    }

    public void launchReservation(View v) {
        Intent i = new Intent(Intent.ACTION_VIEW);
        i.setData(Uri.parse(url));
        startActivity(i);
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        getMenuInflater().inflate(R.menu.menu_events, menu);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        switch (item.getItemId()) {
            case R.id.event_alerts:
                Intent intent = new Intent(AfficherEvent.this, Alerts.class);
                intent.putExtra("idEvent", eventID);
                startActivity(intent);
                break;
        }
        if (mDrawerToggle.onOptionsItemSelected(item)) {
            return true;
        }
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
        Intent intent = new Intent(AfficherEvent.this, Login.class);
        startActivity(intent);
        finish();
    }

    private void addDrawerItems() {
        final String[] osArray;
        osArray = new String[] {getString(R.string.agenda), getString(R.string.listUsers), getString(R.string.messagerie), getString(R.string.profil), getString(R.string.logout)};

        mAdapter = new ArrayAdapter<String>(this, android.R.layout.simple_list_item_1, osArray);
        mDrawerList.setAdapter(mAdapter);

        mDrawerList.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
                if (position == 0) {
                    onBackPressed();
                }
                if (position == 1) {
                    Intent intent = new Intent(AfficherEvent.this, ListUsers.class);
                    startActivity(intent);
                }
                if (position == 2) {
                    Intent intent = new Intent(AfficherEvent.this, Messagerie.class);
                    startActivity(intent);
                }
                if (position == 3) {
                    Intent intent = new Intent(AfficherEvent.this, Profil.class);
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
    }
}
