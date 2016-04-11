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
import java.util.ArrayList;

import org.apache.http.HttpResponse;
import org.apache.http.NameValuePair;
import org.apache.http.client.HttpClient;
import org.apache.http.client.ResponseHandler;
import org.apache.http.client.entity.UrlEncodedFormEntity;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.impl.client.BasicResponseHandler;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.message.BasicNameValuePair;
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
            /*String link = "http://91.121.151.137/scripts_android/getAllEvents.php";

            HttpClient client = new DefaultHttpClient();
            HttpGet request = new HttpGet();
            request.setURI(new URI(link));
            HttpResponse response = client.execute(request);
            BufferedReader in = new BufferedReader(new InputStreamReader(response.getEntity().getContent()));
            StringBuffer sb = new StringBuffer("");
            String line="";*/

            HttpClient httpclient = new DefaultHttpClient();
            HttpPost httppost = new HttpPost("http://91.121.151.137/scripts_android/getAllEvents.php");
            ResponseHandler<String> responseHandler = new BasicResponseHandler();
            final String response = httpclient.execute(httppost, responseHandler);
            JSONObject jObj = new JSONObject(response);

            /*while ((line = in.readLine()) != null) {
                sb.append(line);
            }
            in.close();*/

            return response;
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
            //JSONObject jsonObject = new JSONObject(result);

          //  id = jsonObject.getString("id");
          //  title = jsonObject.getString("title");
          //  description = jsonObject.getString("description");
          //  startDate = jsonObject.getString("startDate");
           // endDate = jsonObject.getString("startDate");
        } catch (Exception e) {
            e.printStackTrace();
        }
    }
}