package jiang.com.healthchat;

import android.app.Activity;
import android.content.Context;
import android.content.SharedPreferences;
import android.content.SharedPreferences.Editor;

public class AppPreferences {

	private static String APP_SHARED_PREFS;

	private final String TWITTER_TOKEN = "twitterToken";
	private final String TWITTER_SECRET = "twitterSecret";
	private final String TWITTER_USERNAME = "twitterUserName";

	private SharedPreferences mPrefs;
	private Editor mPrefsEditor;

	public AppPreferences(Context context) {
		APP_SHARED_PREFS = context.getApplicationContext().getPackageName();
		mPrefs = context.getSharedPreferences(APP_SHARED_PREFS,
				Activity.MODE_PRIVATE);
		mPrefsEditor = mPrefs.edit();
	}

	public void setTwitterToken(String token) {
		mPrefsEditor.putString(TWITTER_TOKEN, token);
		mPrefsEditor.commit();
	}

	public String getTwitterToken() {
		String token = mPrefs.getString(TWITTER_TOKEN, "");
		return token;
	}

	public void setTwitterSecret(String secret) {
		mPrefsEditor.putString(TWITTER_SECRET, secret);
		mPrefsEditor.commit();
	}

	public String getTwitterSecret() {
		String secret = mPrefs.getString(TWITTER_SECRET, "");
		return secret;
	}

	public void setTwitterUserName(String username) {
		mPrefsEditor.putString(TWITTER_USERNAME, username);
		mPrefsEditor.commit();
	}

	public String getTwitterUserName() {
		String username = mPrefs.getString(TWITTER_USERNAME, "");
		return username;
	}
}
