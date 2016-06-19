package com.bpho.bpho_music;

import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.net.ConnectivityManager;
import android.os.StrictMode;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;
import android.view.inputmethod.InputMethodManager;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

import org.apache.http.HttpResponse;
import org.apache.http.NameValuePair;
import org.apache.http.client.HttpClient;
import org.apache.http.client.ResponseHandler;
import org.apache.http.client.entity.UrlEncodedFormEntity;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.impl.client.BasicResponseHandler;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.message.BasicNameValuePair;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.List;

public class MdpOublie extends AppCompatActivity {

    Button btnForgotPassword;
    EditText ETmail;
    HttpPost httppost;
    HttpClient httpclient;
    List<NameValuePair> nameValuePairs;
    private SessionManager session;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.content_mdpoublie);
        getSupportActionBar().setTitle(getString(R.string.forgotPassword));
        if (android.os.Build.VERSION.SDK_INT > 9) {
            StrictMode.ThreadPolicy policy = new StrictMode.ThreadPolicy.Builder().permitAll().build();
            StrictMode.setThreadPolicy(policy);
        }
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);

        btnForgotPassword = (Button) findViewById(R.id.btnForgotPassword);
        ETmail = (EditText) findViewById(R.id.ETmail);

        session = new SessionManager(getApplicationContext());

        if (session.isLoggedIn()) {
            Intent intent = new Intent(MdpOublie.this, Agenda.class);
            startActivity(intent);
        }

        btnForgotPassword.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                String response = recup();
                Toast.makeText(MdpOublie.this, response, Toast.LENGTH_SHORT).show();
                InputMethodManager imm = (InputMethodManager) getSystemService(Context.INPUT_METHOD_SERVICE);
                imm.hideSoftInputFromWindow(ETmail.getWindowToken(), 0);
            }
        });
    }

    private boolean checkInternet() {
        ConnectivityManager con=(ConnectivityManager)getSystemService(MdpOublie.CONNECTIVITY_SERVICE);
        boolean wifi=con.getNetworkInfo(ConnectivityManager.TYPE_WIFI).isConnectedOrConnecting();
        boolean internet=con.getNetworkInfo(ConnectivityManager.TYPE_MOBILE).isConnectedOrConnecting();
        //check Internet connection
        if(internet||wifi)
        {
            return true;
        }else{
            new AlertDialog.Builder(this)
                    .setIcon(R.drawable.logo)
                    .setTitle(getString(R.string.noInternet))
                    .setMessage(getString(R.string.internetRequest))
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

    public String recup(){
        if(checkInternet()) {
            try {

                httpclient = new DefaultHttpClient();
                httppost = new HttpPost("http://91.121.151.137/TFE/php/mdpOublie.php");
                nameValuePairs = new ArrayList<NameValuePair>(1);
                nameValuePairs.add(new BasicNameValuePair("mail", ETmail.getText().toString().trim()));
                httppost.setEntity(new UrlEncodedFormEntity(nameValuePairs));

                ResponseHandler<String> responseHandler = new BasicResponseHandler();
                final String response = httpclient.execute(httppost, responseHandler);
                JSONObject jObj = new JSONObject(response);

                Boolean check = jObj.getBoolean("check");

                if (check) {
                    return getString(R.string.resetPasswordSent);
                } else {
                    return getString(R.string.incorrectMail);
                }

            } catch (Exception e) {
                System.out.println("Exception : " + e.getMessage());
                Toast.makeText(this, getString(R.string.noInternet), Toast.LENGTH_SHORT).show();
            }
        }
        return null;
    }
}
