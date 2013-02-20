package app.cookingplace;

import android.app.Activity;
import android.app.AlertDialog;
import android.content.Intent;
import android.os.Bundle;
import android.view.Menu;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.Toast;

public class MainActivity extends Activity implements OnClickListener {

	private Button show_event_button;
	private Button create_event__button;
	private Button show_maps_button;
	private String extraCurrentUserId = "";
	private String extraCreated = "";
	private String extraLogonName = "";

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_main);

		Bundle extras = getIntent().getExtras();
		extraCurrentUserId = extras.getString("userId");
		extraLogonName = extras.getString("currentLogonName");
		try {
			extraCreated = extras.getString("created");
			if (extraCreated.equals("1")) {
				Toast.makeText(getApplicationContext(),
						"Event successful created.", Toast.LENGTH_LONG).show();
			}else if (extras.getString("created").equals("0")) {
				new AlertDialog.Builder(this).setMessage("Error on Event create. Try again an stay close to the hints.")
				.setNeutralButton(R.string.error_ok, null).show();
			} else {
				 Toast.makeText(getApplicationContext(),
				 "Select one funtion.", Toast.LENGTH_LONG)
				 .show();
			}
		} catch (Exception e) {
			// TODO: handle exception
		}

		show_event_button = (Button) findViewById(R.id.tab1);
		show_event_button.setOnClickListener(this);

		create_event__button = (Button) findViewById(R.id.tab2);
		create_event__button.setOnClickListener(this);

		show_maps_button = (Button) findViewById(R.id.tab3);
		show_maps_button.setOnClickListener(this);
	}

	@Override
	public boolean onCreateOptionsMenu(Menu menu) {
		// Inflate the menu; this adds items to the action bar if it is present.
		getMenuInflater().inflate(R.menu.activity_main, menu);
		return true;
	}

	@Override
	public void onClick(View view) {
		Intent nextScreen = null;
		switch (view.getId()) {
		case R.id.tab1:
			// Loading Search Event Activety
			nextScreen = new Intent(MainActivity.this,
					SearchEventActivity.class);
			nextScreen.putExtra("userId", "" + extraCurrentUserId);
			nextScreen.putExtra("currentLogonName", extraLogonName);
			nextScreen.putExtra("eventId", "");
			startActivityForResult(nextScreen, 0);
			break;
		case R.id.tab2:
			// Loading Create Event Activety
			nextScreen = new Intent(MainActivity.this,
					CreateEventActivity.class);
			nextScreen.putExtra("userId", "" + extraCurrentUserId);
			nextScreen.putExtra("currentLogonName", extraLogonName);
			nextScreen.putExtra("eventId", "");
			startActivityForResult(nextScreen, 0);
			break;
		case R.id.tab3:
			// Loading Maps Activety
			nextScreen = new Intent(MainActivity.this, MapsActivity.class);
			nextScreen.putExtra("userId", "" + extraCurrentUserId);
			nextScreen.putExtra("currentLogonName", extraLogonName);
			nextScreen.putExtra("eventId", "");
			startActivityForResult(nextScreen, 0);
			break;
		}
	}
}
