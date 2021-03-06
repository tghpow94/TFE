package com.bpho.bpho_spect;

import android.app.Activity;
import android.app.AlertDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.net.ConnectivityManager;
import android.os.Bundle;
import android.support.design.widget.FloatingActionButton;
import android.support.design.widget.Snackbar;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.Toolbar;
import android.view.MotionEvent;
import android.view.View;
import android.view.Menu;
import android.view.MenuItem;
import android.view.ViewGroup;
import android.view.WindowManager;
import android.view.inputmethod.InputMethodManager;
import android.widget.Button;
import android.widget.EditText;
import android.widget.RelativeLayout;
import android.widget.ScrollView;
import android.widget.Toast;

import org.apache.http.NameValuePair;
import org.apache.http.client.HttpClient;
import org.apache.http.client.ResponseHandler;
import org.apache.http.client.entity.UrlEncodedFormEntity;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.impl.client.BasicResponseHandler;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.message.BasicNameValuePair;

import java.util.ArrayList;
import java.util.Locale;

/**
 * Created by Thomas on 19/03/2016.
 */
public class Contact extends AppCompatActivity {
    EditText ETName, ETMail, ETMessage;
    String name, mail, message;
    RelativeLayout test;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.layout_contact);
        Toolbar toolbar = (Toolbar) findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);
        getSupportActionBar().setTitle(getString(R.string.contact_title));
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);

        setupUI(findViewById(R.id.parent));

        ETName = (EditText) findViewById(R.id.inputName);
        ETMail = (EditText) findViewById(R.id.inputEmail);
        ETMessage = (EditText) findViewById(R.id.inputMessage);
        test = (RelativeLayout) findViewById(R.id.test);
        test.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                InputMethodManager imm = (InputMethodManager) getSystemService(Context.INPUT_METHOD_SERVICE);
                imm.hideSoftInputFromWindow(ETMessage.getWindowToken(), 0);
            }
        });

        ETMessage.setOnFocusChangeListener(new View.OnFocusChangeListener() {
            @Override
            public void onFocusChange(View v, boolean hasFocus) {
                if (hasFocus) {
                    ETMessage.setHint("");
                    ETMessage.setTextAlignment(View.TEXT_ALIGNMENT_VIEW_START);
                } else {
                    if (ETMessage.getText().toString().equals("")) {
                        ETMessage.setHint(getString(R.string.message));
                        ETMessage.setTextAlignment(View.TEXT_ALIGNMENT_CENTER);
                    }
                }
            }
        });
    }

    private boolean checkInternet() {
        ConnectivityManager con=(ConnectivityManager)getSystemService(Contact.CONNECTIVITY_SERVICE);
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

    public void sendMessage(View v) {
        if(checkInternet()) {
            name = ETName.getText().toString().trim();
            mail = ETMail.getText().toString().trim();
            message = ETMessage.getText().toString().trim();
            try {
                if (name.equals("") || mail.equals("") || message.equals("")) {
                    Snackbar.make(v, getString(R.string.missingData), Snackbar.LENGTH_LONG).setAction("Action", null).show();
                } else {
                    HttpClient httpclient = new DefaultHttpClient();
                    HttpPost httppost = new HttpPost("http://91.121.151.137/TFE/php/sendMessage.php");
                    ResponseHandler<String> responseHandler = new BasicResponseHandler();
                    ArrayList<NameValuePair> nameValuePairs = new ArrayList<NameValuePair>(4);
                    nameValuePairs.add(new BasicNameValuePair("name", name));
                    nameValuePairs.add(new BasicNameValuePair("mail", mail));
                    nameValuePairs.add(new BasicNameValuePair("message", message));
                    nameValuePairs.add(new BasicNameValuePair("lang", Locale.getDefault().getLanguage()));
                    httppost.setEntity(new UrlEncodedFormEntity(nameValuePairs));
                    final String response = httpclient.execute(httppost, responseHandler);
                    if (response.equals("false")) {
                        Snackbar.make(v, getString(R.string.incorrectDataMail), Snackbar.LENGTH_LONG).setAction("Action", null).show();
                    } else {
                        hideSoftKeyboard(Contact.this);
                        Toast.makeText(this, getString(R.string.mailSent), Toast.LENGTH_SHORT).show();
                    }
                }

            } catch (Exception e) {
                e.printStackTrace();
            }
        }
    }

    public void hideSoftKeyboard(Activity activity) {
        InputMethodManager inputMethodManager = (InputMethodManager)  activity.getSystemService(Activity.INPUT_METHOD_SERVICE);
        inputMethodManager.hideSoftInputFromWindow(activity.getCurrentFocus().getWindowToken(), 0);
        View v = getCurrentFocus();
        v.clearFocus();
    }

    public void setupUI(View view) {
        if(!(view instanceof EditText || view instanceof Button)) {
            view.setOnTouchListener(new View.OnTouchListener() {

                public boolean onTouch(View v, MotionEvent event) {
                    hideSoftKeyboard(Contact.this);
                    return false;
                }

            });
        }
        if (view instanceof ViewGroup) {
            for (int i = 0; i < ((ViewGroup) view).getChildCount(); i++) {
                View innerView = ((ViewGroup) view).getChildAt(i);
                setupUI(innerView);
            }
        }
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
                startActivity(new Intent(Contact.this, InfosGenerales.class));
        }
        return super.onOptionsItemSelected(item);
    }
}