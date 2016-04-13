package com.bpho.bpho_spect;

import android.app.ProgressDialog;
import android.content.Intent;
import android.graphics.Color;
import android.graphics.drawable.ColorDrawable;
import android.os.Bundle;
import android.os.StrictMode;
import android.support.design.widget.FloatingActionButton;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.Toolbar;
import android.text.TextUtils;
import android.util.Log;
import android.view.View;
import android.view.Menu;
import android.view.MenuItem;
import android.widget.Button;
import android.widget.ListView;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.VolleyLog;
import com.android.volley.toolbox.ImageLoader;
import com.android.volley.toolbox.JsonArrayRequest;
import com.android.volley.toolbox.Volley;

import org.apache.http.client.HttpClient;
import org.apache.http.client.ResponseHandler;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.impl.client.BasicResponseHandler;
import org.apache.http.impl.client.DefaultHttpClient;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.Date;
import java.util.List;

public class Agenda extends AppCompatActivity {
    private static final String TAG = Agenda.class.getSimpleName();

    private static final String url = "http://91.121.151.137/scripts_android/getAllEvents.php";
    private ProgressDialog pDialog;
    private List<Event> eventList = new ArrayList<Event>();
    private ListView listView;
    private CustomListAdapter adapter;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.layout_agenda);
        Toolbar toolbar = (Toolbar) findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);
        getSupportActionBar().setTitle("Agenda du BPHO");
        if (android.os.Build.VERSION.SDK_INT > 9) {
            StrictMode.ThreadPolicy policy = new StrictMode.ThreadPolicy.Builder().permitAll().build();
            StrictMode.setThreadPolicy(policy);
        }

        listView = (ListView) findViewById(R.id.list);
        adapter = new CustomListAdapter(this, eventList);
        listView.setAdapter(adapter);

        pDialog = new ProgressDialog(this);
        pDialog.setMessage("Loading...");
        pDialog.show();

        JSONArray response = null;
        try {
            response = new JSONArray(getEvents());
        } catch (Exception e) {
            e.printStackTrace();
        }

        // Parsing json
        for (int i = 0; i < response.length(); i++) {
            try {
                JSONObject obj = response.getJSONObject(i);
                Event event = new Event();
                event.setTitle(obj.getString("title"));
                //event.setThumbnailUrl(obj.getString("image"));
                event.setStartDate(obj.getString("startDate"));
                event.setEndDate(obj.getString("endDate"));
                event.setDate(event.getStartDate(), event.getEndDate());
                event.setCity(obj.getString("city"));
                event.setCityCode(obj.getString("cityCode"));
                event.setAddress(obj.getString("address"));
                event.setAddressInfos(obj.getString("addressInfos"));
                event.setFullAddress(event.getAddressInfos(), event.getAddress(), event.getCity(), event.getCityCode());
                event.setPrice(obj.getString("price"));
                event.setReservationLink(obj.getString("reservation"));

                // adding event to events array
                eventList.add(event);

            } catch (JSONException e) {
                e.printStackTrace();
            }

        }

        // notifying list adapter about data changes
        // so that it renders the list view with updated data
        adapter.notifyDataSetChanged();

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
        try {
            HttpClient httpclient = new DefaultHttpClient();
            HttpPost httppost = new HttpPost("http://91.121.151.137/scripts_android/getAllEvents.php");
            ResponseHandler<String> responseHandler = new BasicResponseHandler();
            System.out.println("avant execute");
            final String response = httpclient.execute(httppost, responseHandler);
            System.out.println("apres execute" + response);
            hidePDialog();
            return response;
        } catch (Exception e) {
            e.printStackTrace();
        }
        hidePDialog();
        return "echec";
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
            case R.id.action_generalInfos:
                startActivity(new Intent(Agenda.this, InfosGenerales.class));
            default:
                return super.onOptionsItemSelected(item);
        }
    }
}
