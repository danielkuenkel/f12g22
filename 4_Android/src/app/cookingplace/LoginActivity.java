package app.cookingplace;

import org.w3c.dom.Document;
import org.w3c.dom.Element;
import org.w3c.dom.NodeList;

import android.app.Activity;
import android.app.AlertDialog;
import android.content.Context;
import android.content.Intent;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.os.Bundle;
import android.view.Menu;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.EditText;

public class LoginActivity extends Activity implements OnClickListener {

	// All static variables
	static final String baseURL = "http://sfsuswe.com/~f12g22/web/php/Login.php?";
	String URL = "";
	// XML node keys
	static final String KEY_SESSION = "session"; // parent node
	static final String KEY_ACTIVATED = "activate";
	String activated = "";
	static final String KEY_REGISTERED = "registered";
	String registered = "";
	static final String KEY_USERID = "userId";
	String userId = "";
	static final String KEY_LOGONNAME = "currentLogonName";
	String currentLogonName = "";

	private Button login_button;

	public boolean isOnline() {
		ConnectivityManager cm = (ConnectivityManager) getSystemService(Context.CONNECTIVITY_SERVICE);
		NetworkInfo netInfo = cm.getActiveNetworkInfo();
		if (netInfo != null && netInfo.isConnected()) {
			return true;
		}
		new AlertDialog.Builder(this)
				.setMessage("Device offline! Connect to Internet.")
				.setNeutralButton(R.string.error_ok, null).show();
		return false;
	}

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_login);
		login_button = (Button) findViewById(R.id.login_button);
		login_button.setOnClickListener(this);
	}

	@Override
	public boolean onCreateOptionsMenu(Menu menu) {
		// Inflate the menu; this adds items to the action bar if it is present.
		getMenuInflater().inflate(R.menu.activity_login, menu);
		return true;
	}

	@Override
	public void onClick(View view) {
		if (isOnline()) {
			EditText nameLogin = (EditText) findViewById(R.id.user_logon_id);
			String loginName = nameLogin.getText().toString();
			EditText passwordLogin = (EditText) findViewById(R.id.password_logon_id);
			String password = passwordLogin.getText().toString();

			String parameters = "logon=" + loginName + "&password=" + password;
			URL = baseURL + parameters;

			XMLParser parser = new XMLParser();
			String xml = parser.getXmlFromUrl(URL); // getting XML
			Document doc = parser.getDomElement(xml); // getting DOM element

			NodeList nl = doc.getElementsByTagName(KEY_SESSION);

			Element e = (Element) nl.item(0);
			// adding each child node to HashMap key => value
			activated = parser.getValue(e, KEY_ACTIVATED);
			registered = parser.getValue(e, KEY_REGISTERED);
			userId = parser.getValue(e, KEY_USERID);
			currentLogonName = parser.getValue(e, KEY_LOGONNAME);

			// //Loading Map Activety
			if (registered.equals("1")) {
				Intent nextScreen = new Intent(LoginActivity.this,
						MainActivity.class);
				nextScreen.putExtra("userId", userId);
				nextScreen.putExtra("created", "");
				nextScreen.putExtra("currentLogonName", loginName);

				startActivityForResult(nextScreen, 0);
			} else {
				new AlertDialog.Builder(this)
						.setMessage(
								"You are not registered, please register on www.sfsuswe.com/~f12g22/")
						.setNeutralButton(R.string.error_ok, null).show();
			}
		}
	}
}
