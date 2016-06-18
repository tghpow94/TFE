package com.bpho.bpho_music;

import android.content.Context;
import android.content.SharedPreferences;
import android.util.Log;

public class SessionManager {
    // LogCat tag
    private static String TAG = SessionManager.class.getSimpleName();

    // Shared Preferences
    SharedPreferences pref;

    SharedPreferences.Editor editor;
    Context _context;
    String id, email, name, firstName, tel, dateLastConnect, dateRegister;

    // Shared pref mode
    int PRIVATE_MODE = 0;

    // Shared preferences file name
    private static final String PREF_NAME = "AndroidHiveLogin";

    private static final String KEY_IS_LOGGEDIN = "isLoggedIn";

    public SessionManager(Context context) {
        this._context = context;
        pref = _context.getSharedPreferences(PREF_NAME, PRIVATE_MODE);
        editor = pref.edit();
    }

    public void setLogin(boolean isLoggedIn) {
        editor.putBoolean(KEY_IS_LOGGEDIN, isLoggedIn);
        editor.commit();
        Log.d(TAG, "Users login session modified!");
    }

    public boolean isLoggedIn(){
        return pref.getBoolean(KEY_IS_LOGGEDIN, false);
    }

    public void setLastName(String Name) {
        editor.putString("LASTNAME", Name);
        editor.commit();
    }

    public String getLastName(){
        return pref.getString("LASTNAME", null);
    }

    public void setFirstName(String Name) {
        editor.putString("FIRSTNAME", Name);
        editor.commit();
    }

    public String getFirstName(){
        return pref.getString("FIRSTNAME", null);
    }

    public void setDroit(String droit) {
        editor.putString("DROIT", droit);
        editor.commit();
    }

    public String getDroit(){
        return pref.getString("DROIT", null);
    }

    public String getTel() {
        return pref.getString("TEL", null);
    }

    public void setTel(String tel) {
        if (tel == "null") {
            editor.putString("TEL", "");
        } else {
            editor.putString("TEL", tel);
        }
        editor.commit();
    }

    public String getInscription() {
        return pref.getString("INSCRIPTION", null);
    }

    public void setInscription(String inscription) {
        editor.putString("INSCRIPTION", inscription);
        editor.commit();
    }

    public String getLastConnect() {
        return pref.getString("LASTCONNECT", null);
    }

    public void setLastConnect(String lastConnect) {
        editor.putString("LASTCONNECT", lastConnect);
        editor.commit();
    }

    public void setEmail(String email) {
        editor.putString("EMAIL",email );
        editor.commit();
    }

    public String getEmail(){
        return pref.getString("EMAIL",null);

    }

    public void setId(String id) {
        editor.putString("ID",id );
        editor.commit();

    }

    public String getId(){
        return pref.getString("ID",null);

    }
}
