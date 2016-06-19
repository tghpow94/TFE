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
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;
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

public class Login extends AppCompatActivity {

    Button btnConnexion;
    TextView mdpForget;
    EditText ETmail, ETpassword;
    private SessionManager session;
    ProgressDialog dialog = null;
    HttpPost httppost;
    HttpClient httpclient;
    List<NameValuePair> nameValuePairs;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.content_login);
        getSupportActionBar().setTitle(getString(R.string.welcome));
        if (android.os.Build.VERSION.SDK_INT > 9) {
            StrictMode.ThreadPolicy policy = new StrictMode.ThreadPolicy.Builder().permitAll().build();
            StrictMode.setThreadPolicy(policy);
        }

        btnConnexion = (Button)findViewById(R.id.btnConnexion);
        mdpForget= (TextView)findViewById(R.id.mdpForget);
        ETmail = (EditText)findViewById(R.id.ETmail);
        ETpassword = (EditText)findViewById(R.id.ETpassword);

        session = new SessionManager(getApplicationContext());

        if (session.isLoggedIn()) {
            Intent intent = new Intent(Login.this, Agenda.class);
            startActivity(intent);
            logoutUser();
        }

        btnConnexion.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                dialog = ProgressDialog.show(Login.this, "", getString(R.string.validateUser), true);
                login();
            }
        });

        mdpForget.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent intent = new Intent(Login.this, MdpOublie.class);
                startActivity(intent);
            }
        });
    }

    private boolean checkInternet() {
        ConnectivityManager con=(ConnectivityManager)getSystemService(Login.CONNECTIVITY_SERVICE);
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
            runOnUiThread(new Runnable() {
                public void run() {
                    dialog.dismiss();
                }
            });
            return false;
        }
    }

    public void login(){
        if(checkInternet()) {
            try {

                httpclient = new DefaultHttpClient();
                httppost = new HttpPost("http://91.121.151.137/TFE/php/login.php");
                nameValuePairs = new ArrayList<NameValuePair>(2);
                nameValuePairs.add(new BasicNameValuePair("mail", ETmail.getText().toString().trim()));
                nameValuePairs.add(new BasicNameValuePair("mdp", ETpassword.getText().toString().trim()));
                httppost.setEntity(new UrlEncodedFormEntity(nameValuePairs));

                ResponseHandler<String> responseHandler = new BasicResponseHandler();
                final String response = httpclient.execute(httppost, responseHandler);
                JSONObject jObj = new JSONObject(response);

                final boolean error = jObj.getBoolean("error");

                if (error == true) {
                    Toast.makeText(Login.this, getString(R.string.wrongConnect), Toast.LENGTH_SHORT).show();
                } else {
                    final int id = jObj.getInt("id");
                    final String firstName = jObj.getString("firstName");
                    final String lastName = jObj.getString("name");
                    final String email = jObj.getString("email");
                    final int droit = jObj.getInt("droit");
                    final String tel = jObj.getString("phone");
                    final String dateInscription = jObj.getString("dateRegister");
                    final String lastConnect = jObj.getString("dateLastConnect");

                    session.setLogin(true);
                    session.setId(String.valueOf(id));
                    session.setEmail(email);
                    session.setFirstName(firstName);
                    session.setLastName(lastName);
                    session.setDroit(String.valueOf(droit));
                    session.setTel(tel);
                    session.setInscription(dateInscription);
                    session.setLastConnect(lastConnect);

                    runOnUiThread(new Runnable() {
                        public void run() {
                            Toast.makeText(Login.this, getString(R.string.connected), Toast.LENGTH_SHORT).show();
                        }
                    });
                    startActivity(new Intent(Login.this, Agenda.class));
                }

                runOnUiThread(new Runnable() {
                    public void run() {
                        dialog.dismiss();
                    }
                });

            } catch (Exception e) {
                dialog.dismiss();
                System.out.println("Exception : " + e.getMessage());
                Toast.makeText(this, getString(R.string.noInternet), Toast.LENGTH_SHORT).show();
            }
        }
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

        Intent intent = new Intent(Login.this, Login.class);
        startActivity(intent);
        finish();
    }

}
