package app.cookingplace;

import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.view.Menu;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

public class CreateEventActivity extends Activity implements
		android.view.View.OnClickListener {

	private Button next;
	private String extraCurrentUserId = "";
	private String extraEventId = "";
	private String extraCurrentLogonname = "";
	private String extraEventTitle = "";
	private String extraEventMaxPersons = "";
	private String extraEventDate = "";
	private String extraEventHour = "";
	private String extraEventMinute = "";
	private String extraEventCost = "";
	private String extraEventStreet = "";
	private String extraEventHouseNumber = "";
	private String extraEventZip = "";
	private String extraEventCity = "";
	private String extraEventAbstract = "";

	EditText eventTitle = null;
	EditText eventMaxPersons = null;
	EditText eventCost = null;
	EditText eventDate = null;
	EditText eventHour = null;
	EditText eventMinute = null;
	private String eventTitleString = "";
	private String eventMaxPersonsString = "";
	private String eventCostString = "";
	private String eventDateString = "";
	private String eventHourString = "";
	private String eventMinuteString = "";

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_create_event);
		next = (Button) findViewById(R.id.createEventNextButton);
		next.setOnClickListener(this);

		Bundle extras = getIntent().getExtras();
		try {
			extraEventId = extras.getString("eventId");

			extraEventTitle = extras.getString("eventTitle");
			extraEventMaxPersons = extras.getString("eventMaxPersons");
			extraEventCost = extras.getString("eventCost");
			extraEventDate = extras.getString("eventDate");
			extraEventHour = extras.getString("eventHour");
			extraEventMinute = extras.getString("eventMinute");

			extraEventStreet = extras.getString("eventStreet");
			extraEventHouseNumber = extras.getString("eventHouseNumber");
			extraEventZip = extras.getString("eventZip");
			extraEventCity = extras.getString("eventCity");
			extraEventAbstract = extras.getString("eventAbstract");

		} catch (Exception e) {
			// TODO: handle exception
		}
		extraCurrentLogonname = extras.getString("currentLogonName");
		extraCurrentUserId = extras.getString("userId");
		eventTitle = (EditText) findViewById(R.id.eventTitle_create);
		eventMaxPersons = (EditText) findViewById(R.id.eventMaxPersons_create);
		eventCost = (EditText) findViewById(R.id.eventCost_create);
		eventDate = (EditText) findViewById(R.id.eventDate_create);
		eventHour = (EditText) findViewById(R.id.eventHour_create);
		eventMinute = (EditText) findViewById(R.id.eventMinute_create);

		try {
			if (!extraEventId.equals("")) {
				eventTitle.setText(extraEventTitle);
				eventMaxPersons.setText(extraEventMaxPersons);
				eventCost.setText(extraEventCost);
				eventDate.setText(extraEventDate);
				eventHour.setText(extraEventHour);
				eventMinute.setText(extraEventMinute);
			}
		} catch (Exception e) {
			// TODO: handle exception
		}

	}

	@Override
	public boolean onCreateOptionsMenu(Menu menu) {
		// Inflate the menu; this adds items to the action bar if it is present.
		getMenuInflater().inflate(R.menu.activity_create_event, menu);
		return true;
	}

	@Override
	public void onClick(View v) {
		try {
			if (!extraEventId.equals("")) {

				Intent nextScreen = new Intent(CreateEventActivity.this,
						CreateEventAddressActivity.class);
				nextScreen.putExtra("eventId", extraEventId);
				nextScreen.putExtra("currentLogonName", extraCurrentLogonname);
				nextScreen.putExtra("eventTitle", "" + eventTitle.getText());
				nextScreen.putExtra("eventMaxPersons", ""
						+ extraEventMaxPersons);
				nextScreen.putExtra("eventCost", "" + eventCost.getText());
				nextScreen.putExtra("eventDate", "" + eventDate.getText());
				nextScreen.putExtra("eventHour", "" + eventHour.getText());
				nextScreen.putExtra("eventMinute", "" + eventMinute.getText());
				nextScreen.putExtra("userId", "" + extraCurrentUserId);

				nextScreen.putExtra("eventStreet", "" + extraEventStreet);
				nextScreen.putExtra("eventHouseNumber", ""
						+ extraEventHouseNumber);
				nextScreen.putExtra("eventZip", "" + extraEventZip);
				nextScreen.putExtra("eventCity", "" + extraEventCity);
				nextScreen.putExtra("eventAbstract", "" + extraEventAbstract);

				startActivityForResult(nextScreen, 0);
			}
		} catch (Exception e) {
			// TODO: handle exception
		}
		eventTitleString = eventTitle.getText().toString();
		eventMaxPersonsString = eventMaxPersons.getText().toString();
		eventCostString = eventCost.getText().toString();
		eventDateString = eventDate.getText().toString();
		eventHourString = eventHour.getText().toString();
		eventMinuteString = eventMinute.getText().toString();

		try {
			if (extraEventId.equals("")) {
				Intent nextScreen = new Intent(CreateEventActivity.this,
						CreateEventAddressActivity.class);
				nextScreen.putExtra("eventTitle", "" + eventTitleString);
				nextScreen.putExtra("eventMaxPersons", ""
						+ eventMaxPersonsString);
				nextScreen.putExtra("eventCost", "" + eventCostString);
				nextScreen.putExtra("eventDate", "" + eventDateString);
				nextScreen.putExtra("eventHour", "" + eventHourString);
				nextScreen.putExtra("eventMinute", "" + eventMinuteString);
				nextScreen.putExtra("userId", "" + extraCurrentUserId);

				startActivityForResult(nextScreen, 0);
			}
		} catch (Exception e) {
			// TODO: handle exception
		}

	}

}
