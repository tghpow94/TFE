package com.bpho.bpho_spect;

import android.content.Intent;
import android.os.Bundle;
import android.os.StrictMode;
import android.support.design.widget.FloatingActionButton;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.Toolbar;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.TextView;

import com.android.volley.toolbox.ImageLoader;
import com.android.volley.toolbox.NetworkImageView;

/**
 * Created by Thomas on 12/04/2016.
 */
public class AfficherEvent extends AppCompatActivity {

    NetworkImageView thumbNail;
    TextView title, date, address, description;
    ImageLoader imageLoader = AppController.getInstance().getImageLoader();

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.layout_event);
        Toolbar toolbar = (Toolbar) findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);
        getSupportActionBar().setTitle("Event");
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);
        if (android.os.Build.VERSION.SDK_INT > 9) {
            StrictMode.ThreadPolicy policy = new StrictMode.ThreadPolicy.Builder().permitAll().build();
            StrictMode.setThreadPolicy(policy);
        }

        FloatingActionButton fab = (FloatingActionButton) findViewById(R.id.fab);
        fab.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(AfficherEvent.this, Contact.class);
                startActivity(intent);
            }
        });

        thumbNail = (NetworkImageView) findViewById(R.id.thumbnail);
        title = (TextView) findViewById(R.id.title);
        date = (TextView) findViewById(R.id.date);
        address = (TextView) findViewById(R.id.address);
        description = (TextView) findViewById(R.id.description);
        Event event = (Event)getIntent().getSerializableExtra("event");

        imageLoader = AppController.getInstance().getImageLoader();
        thumbNail.setImageUrl(event.getThumbnailUrl(), imageLoader);
        title.setText(event.getTitle());
        date.setText(event.getDate());
        address.setText(event.getFullAddress());
        description.setText(event.getDescription());

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
                startActivity(new Intent(AfficherEvent.this, InfosGenerales.class));
        }
        return super.onOptionsItemSelected(item);
    }
}
