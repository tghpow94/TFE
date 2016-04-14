package com.bpho.bpho_spect;

import android.content.Intent;
import android.graphics.Paint;
import android.net.Uri;
import android.os.Bundle;
import android.os.StrictMode;
import android.support.design.widget.FloatingActionButton;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.Toolbar;
import android.text.Html;
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
    TextView title, date, address, prix, reservation, description;
    ImageLoader imageLoader = AppController.getInstance().getImageLoader();
    String url;

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

        thumbNail = (NetworkImageView) findViewById(R.id.thumbnail);
        title = (TextView) findViewById(R.id.title);
        date = (TextView) findViewById(R.id.date);
        address = (TextView) findViewById(R.id.address);
        description = (TextView) findViewById(R.id.description);
        prix = (TextView) findViewById(R.id.prix);
        reservation = (TextView) findViewById(R.id.reservation);
        Event event = (Event)getIntent().getSerializableExtra("event");

        imageLoader = AppController.getInstance().getImageLoader();
        thumbNail.setImageUrl(event.getThumbnailUrl(), imageLoader);
        title.setText(event.getTitle());
        date.setText(event.getDate());
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
