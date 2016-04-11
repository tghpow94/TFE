package com.bpho.bpho_spect;

/**
 * Created by Thomas on 11/04/2016.
 */
import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.io.OutputStreamWriter;
import java.net.URI;
import java.net.URL;
import java.net.URLConnection;
import java.net.URLEncoder;

import org.apache.http.HttpResponse;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.impl.client.DefaultHttpClient;
import org.json.JSONException;
import org.json.JSONObject;

import android.content.Context;
import android.os.AsyncTask;
import android.widget.TextView;

public class GetEvents  extends AsyncTask<String,Void,String>{
    private Context context;

    public GetEvents(Context context) {
        this.context = context;
    }

    protected void onPreExecute(){
    }

    @Override
    protected String doInBackground(String... arg0) {
        try{
            String link = "http://91.121.151.137/scripts_android/getAllEvents.php";

            HttpClient client = new DefaultHttpClient();
            HttpGet request = new HttpGet();
            request.setURI(new URI(link));
            HttpResponse response = client.execute(request);
            BufferedReader in = new BufferedReader(new InputStreamReader(response.getEntity().getContent()));
            StringBuffer sb = new StringBuffer("");
            String line="";

            while ((line = in.readLine()) != null) {
                sb.append(line);
                break;
            }
            in.close();

            return sb.toString();
        } catch(Exception e){
            return new String("Exception: " + e.getMessage());
        }

    }

    String id;
    String title;
    String description;
    String startDate;
    String endDate;

    @Override
    protected void onPostExecute(String result){
        try {
            JSONObject jsonObject = new JSONObject(result);

          //  id = jsonObject.getString("id");
          //  title = jsonObject.getString("title");
          //  description = jsonObject.getString("description");
          //  startDate = jsonObject.getString("startDate");
           // endDate = jsonObject.getString("startDate");
        } catch (JSONException e) {
            e.printStackTrace();
        }
    }
}