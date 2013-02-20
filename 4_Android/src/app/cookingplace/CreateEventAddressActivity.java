package app.cookingplace;

import org.w3c.dom.Document;
import org.w3c.dom.Element;
import org.w3c.dom.NodeList;

import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.view.Menu;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.EditText;

public class CreateEventAddressActivity extends Activity implements
		OnClickListener {
	// All static variables
	static final String baseURL = "http://sfsuswe.com/~f12g22/web/php/UploadEvent.php?";
	private static final String KEY_SESSION = "event";
	private static final String KEY_CREATED = "created";
	private static final String KEY_EVENTID = "eventId";
	String URL = "";

	private Button create;
	private String extraCurrentUserId = "";
	private String extraCreated = "";
	private String extraEventId = "";
	private String eventId = "";
	private String extraCurrentLogonName = "";

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

	private String eventStreetString = "";
	private String eventHouseNumberString = "";
	private String eventZipString = "";
	private String eventCityString = "";
	private String eventShortDescriptionString = "";

	EditText eventStreet = null;
	EditText eventHouseNumber = null;
	EditText eventZip = null;
	EditText eventCity = null;
	EditText eventShortDescription = null;

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_create_event_address);
		create = (Button) findViewById(R.id.createEventCreateButton);
		create.setOnClickListener(this);

		Bundle extras = getIntent().getExtras();
		extraCurrentUserId = extras.getString("userId");
		extraCurrentLogonName = extras.getString("correntLogonName");
		try {
			extraEventTitle = extras.getString("eventTitle");
			extraEventMaxPersons = extras.getString("eventMaxPersons");
			extraEventDate = extras.getString("eventDate");
			extraEventHour = extras.getString("eventHour");
			extraEventMinute = extras.getString("eventMinute");
			extraEventCost = extras.getString("eventCost");
			extraEventStreet = extras.getString("eventStreet");
			extraEventHouseNumber = extras.getString("eventHouseNumber");
			extraEventZip = extras.getString("eventZip");
			extraEventCity = extras.getString("eventCity");
			extraEventAbstract = extras.getString("eventAbstract");
			extraEventId = extras.getString("eventId");
		} catch (Exception e) {
			// TODO: handle exception
		}

		eventStreet = (EditText) findViewById(R.id.eventStreet_create);
		eventHouseNumber = (EditText) findViewById(R.id.eventHouseNumber_create);
		eventZip = (EditText) findViewById(R.id.eventZip_create);
		eventCity = (EditText) findViewById(R.id.eventCity_create);
		eventShortDescription = (EditText) findViewById(R.id.eventShortDescription_create);

		try {
			if (!extraEventId.equals("")) {
				eventStreet.setText(extraEventStreet);
				eventHouseNumber.setText(extraEventHouseNumber);
				eventZip.setText(extraEventZip);
				eventCity.setText(extraEventCity);
				eventShortDescription.setText(extraEventAbstract);
			}
		} catch (Exception e) {
			// TODO: handle exception
		}

	}

	@Override
	public boolean onCreateOptionsMenu(Menu menu) {
		// Inflate the menu; this adds items to the action bar if it is present.
		getMenuInflater().inflate(R.menu.activity_create_event_address, menu);
		return true;
	}

	@Override
	public void onClick(View v) {
		Bundle extras = getIntent().getExtras();

		// Date from CreateEventActivity
		String eventTitleString = extras.getString("eventTitle");
		String eventMaxPersonsString = extras.getString("eventMaxPersons");
		String eventCostString = extras.getString("eventCost");
		String eventDateString = extras.getString("eventDate");
		String eventHourString = extras.getString("eventHour");
		String eventMinuteString = extras.getString("eventMinute");
		extraCurrentLogonName = extras.getString("currentLogonName");

		try {
			eventId = extras.getString("eventId");
		} catch (Exception e) {
			// TODO: handle exception
		}

		eventStreetString = eventStreet.getText().toString();
		eventHouseNumberString = eventHouseNumber.getText().toString();
		eventZipString = eventZip.getText().toString();
		eventCityString = eventCity.getText().toString();
		eventShortDescriptionString = eventShortDescription.getText()
				.toString();
		URL = baseURL + "updateEventId=" + "&userId=" + extraCurrentUserId
				+ "&eventTitle=" + extraEventTitle + "&maxPersons="
				+ eventMaxPersonsString + "&cost=" + eventCostString
				+ "&date=" + eventDateString + "&hour="
				+ eventHourString + "&minute=" + eventMinuteString
				+ "&street=" + eventStreetString + "&housenumber="
				+ eventHouseNumberString + "&zipcode=" + eventZipString
				+ "&city=" + eventCityString + "&description="
				+ eventShortDescriptionString;
		try {
			if (!extraEventId.equals("")) {
				URL = baseURL + "updateEventId=" + extraEventId + "&userId="
						+ extraCurrentUserId + "&eventTitle=" + eventTitleString
						+ "&maxPersons=" + eventMaxPersonsString + "&cost="
						+ eventCostString + "&date=" + eventDateString
						+ "&hour=" + eventHourString + "&minute="
						+ eventMinuteString + "&street=" + eventStreetString
						+ "&housenumber=" + eventHouseNumberString
						+ "&zipcode=" + eventZipString + "&city="
						+ eventCityString + "&description="
						+ eventShortDescriptionString;
			}
		} catch (Exception e) {
			// TODO: handle exception
		}

		System.out.println("URL: " + URL);
		XMLParser parser = new XMLParser();
		String xml = parser.getXmlFromUrl(URL); // getting XML
		Document doc = parser.getDomElement(xml); // getting DOM element

		NodeList nl = doc.getElementsByTagName(KEY_SESSION);

		Element e = (Element) nl.item(0);

		extraCreated = parser.getValue(e, KEY_CREATED);
		extraEventId = parser.getValue(e, KEY_EVENTID);

		Intent nextScreen = new Intent(CreateEventAddressActivity.this,
				MainActivity.class);
		nextScreen.putExtra("userId", "" + extraCurrentUserId);
		nextScreen.putExtra("eventId", "" + extraEventId);
		nextScreen.putExtra("created", "" + extraCreated);
		nextScreen.putExtra("currentLogonName", extraCurrentLogonName);

		startActivityForResult(nextScreen, 0);
	}
}
