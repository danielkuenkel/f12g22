package app.cookingplace;

import java.sql.Timestamp;
import java.util.Calendar;
import java.util.Date;
import java.util.HashMap;

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
import android.widget.TextView;
import android.widget.Toast;

public class EventActivity extends Activity implements OnClickListener {

	private String extraCurrentUserId;
	private String extraEventId = "";
	private String extraEventTitle = "";
	private String extraUserId = "";
	private String extraLogonName = "";
	private String extraIsOwner = "";
	private String extraAbstract = "";
	private String extraMaxParticipants = "";
	private String extraCost = "";
	private String extraStreet = "";
	private String extraHouseNumber = "";
	private String extraZip = "";
	private String extraCity;
	private String extraTimestamp = "";
	private String extraParticipants = "";
	private String extraCurrentNubmerOfParticipants = "";
	private TextView title;
	private TextView createdBy;
	private TextView abstractString;
	private TextView freeSlots;
	private TextView cost;
	private TextView address;
	private TextView date;
	private TextView currentParticipants;
	private Button join_button;
	private Button edit_button;
	private Button cancel_button;

	protected static final String KEY_EVENT = "event";
	protected static final String KEY_ID = "eventId";
	private String eventId;
	protected static final String KEY_JOINED = "joined";
	String joinedId = "";
	protected static final String KEY_LEAVEID = "joined";
	String leavedId = "";
	protected static final String KEY_STATUS = "status";
	String status = "";

	String dateString = "";
	String hourString = "";
	String minuteString = "";

	static final String basicJoinURL = "http://sfsuswe.com/~f12g22/web/php/JoinEvent.php?";
	String parametersJoin = "";
	String joinURL = "";

	static final String basicLeaveURL = "http://sfsuswe.com/~f12g22/web/php/LeaveEvent.php?";
	String parametersLeave = "";
	String leaveURL = "";

	static final String basicEditURL = "http://sfsuswe.com/~f12g22/web/php/UploadEvent.php?";
	String parametersEdit = "";
	String editURL = "";

	static final String basicCancelURL = "http://sfsuswe.com/~f12g22/web/php/DeleteEvent.php?";

	String parametersCancel = "";
	String cancelURL = "";
	private String extraCurrentLogonName = "";

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_event);
		Bundle extras = getIntent().getExtras();
		extraCurrentUserId = extras.getString("currentUserId");
		extraEventId = extras.getString("eventId");
		extraEventTitle = extras.getString("eventTitle");
		extraUserId = extras.getString("userId");
		extraLogonName = extras.getString("logonName");
		extraIsOwner = extras.getString("isOwner");
		extraAbstract = extras.getString("abstract");
		extraMaxParticipants = extras.getString("maxParticipants");
		extraCost = extras.getString("cost");
		extraStreet = extras.getString("street");
		extraHouseNumber = extras.getString("houseNumber");
		extraZip = extras.getString("zip");
		extraCity = extras.getString("city");
		extraTimestamp = extras.getString("timestamp");
		extraParticipants = extras.getString("participants");
		extraCurrentNubmerOfParticipants = extras
				.getString("currentNumberOfParticipants");
		extraCurrentLogonName = extras.getString("currentLogonName");

		title = (TextView) findViewById(R.id.eventTextView_title);
		title.setText(extraEventTitle);

		createdBy = (TextView) findViewById(R.id.EventTextView_createdBy);
		createdBy.setText(extraLogonName);

		abstractString = (TextView) findViewById(R.id.eventTextView_abstract);
		abstractString.setText(extraAbstract);

		freeSlots = (TextView) findViewById(R.id.eventTextView_freeSlots);
		int cur = Integer.parseInt(extraCurrentNubmerOfParticipants);
		int max = Integer.parseInt(extraMaxParticipants);
		final int free = max - cur;
		freeSlots.setText(extraMaxParticipants + " Total / " + free
				+ "left place(s)");

		cost = (TextView) findViewById(R.id.eventTextView_cost);
		cost.setText(extraCost);

		address = (TextView) findViewById(R.id.eventTextView_address);
		address.setText(extraStreet + " " + extraHouseNumber + ", " + extraZip
				+ " " + extraCity);
		Calendar cal = Calendar.getInstance();
		cal.get(Calendar.YEAR);
		Date calulatedDate = new Date(
				(Long.parseLong(extraTimestamp) * 1000) - 3600000);
		dateString = cal.get(Calendar.DAY_OF_MONTH) + "/"
				+ (cal.get(Calendar.MONTH)+1) + "/" + (cal.get(Calendar.YEAR)+1);
		hourString = "" + calulatedDate.getHours();
		minuteString = "" + calulatedDate.getMinutes();

		date = (TextView) findViewById(R.id.eventTextView_date);
		date.setText(calulatedDate.toString());

		currentParticipants = (TextView) findViewById(R.id.eventTextView_currentParticipants);
		currentParticipants.setText(extraParticipants);
		System.out.println("isOwner: " + extraIsOwner);
		// Is Owner
		if (extraIsOwner.equals("0")) {
			join_button = (Button) findViewById(R.id.join_event_button);
			join_button.setVisibility(View.INVISIBLE);
			edit_button = (Button) findViewById(R.id.edit_event_button);
			edit_button.setVisibility(View.VISIBLE);
			edit_button.setOnClickListener(new OnClickListener() {

				@Override
				public void onClick(View v) {
					Intent nextScreen = null;
					nextScreen = new Intent(EventActivity.this,
							CreateEventActivity.class);
					// For first address Activety
					nextScreen.putExtra("currentUserId", ""
							+ extraCurrentUserId);
					nextScreen.putExtra("currentLogonName", extraLogonName);
					nextScreen.putExtra("eventId", extraEventId);
					nextScreen.putExtra("userId", "" + extraUserId);
					nextScreen.putExtra("eventTitle", "" + extraEventTitle);
					nextScreen.putExtra("eventMaxPersons", ""
							+ extraMaxParticipants);
					nextScreen.putExtra("eventCost", "" + extraCost);
					nextScreen.putExtra("eventDate", "" + dateString);
					nextScreen.putExtra("eventHour", "" + hourString);
					nextScreen.putExtra("eventMinute", "" + minuteString);
					// For second Activety
					nextScreen.putExtra("eventStreet", "" + extraStreet);
					nextScreen.putExtra("eventHouseNumber", ""
							+ extraHouseNumber);
					nextScreen.putExtra("eventZip", "" + extraZip);
					nextScreen.putExtra("eventCity", "" + extraCity);
					nextScreen.putExtra("eventAbstract", "" + extraAbstract);

					startActivityForResult(nextScreen, 0);
				}
			});
			cancel_button = (Button) findViewById(R.id.cancel_event_button);
			cancel_button.setVisibility(View.VISIBLE);
			cancel_button.setOnClickListener(new OnClickListener() {

				@Override
				public void onClick(View v) {
					XMLParser parser = new XMLParser();
					parametersCancel = "eventId=" + extraEventId;
					cancelURL = basicCancelURL + parametersCancel;
					String cancelXml = parser.getXmlFromUrl(cancelURL); // getting
					// XML
					Document doc = parser.getDomElement(cancelXml); // getting
																	// DOM
																	// element

					NodeList xmlElements = doc.getElementsByTagName(KEY_EVENT);
					for (int i = 0; i < xmlElements.getLength(); i++) {
						// creating new HashMap
						HashMap<String, String> eventJoinedMap = new HashMap<String, String>();
						Element e = (Element) xmlElements.item(i);
						// adding each child node to HashMap key => value
						status = parser.getValue(e, KEY_STATUS);
						eventJoinedMap.put(KEY_STATUS, status);
						eventId = parser.getValue(e, KEY_ID);
						eventJoinedMap.put(KEY_ID, eventId);
						// Delete event
						if (status.equals("okay")) {

							Intent nextScreen = null;
							nextScreen = new Intent(EventActivity.this,
									MainActivity.class);
							nextScreen.putExtra("userId", ""
									+ extraCurrentUserId);
							nextScreen.putExtra("currentLogonName",
									extraCurrentLogonName);
							startActivityForResult(nextScreen, 0);

							finish();
							join_button.setText("Leave Event");
							Toast.makeText(getApplicationContext(),
									"Event deleted.", Toast.LENGTH_LONG).show();
							// Event not deleted
						} else if (status.equals("error")) {
							Intent nextScreen = null;
							nextScreen = new Intent(EventActivity.this,
									MainActivity.class);
							nextScreen.putExtra("userId", ""
									+ extraCurrentUserId);
							nextScreen.putExtra("currentLogonName",
									extraCurrentLogonName);
							startActivityForResult(nextScreen, 0);

							finish();

							Toast.makeText(getApplicationContext(),
									"Event not deleted.", Toast.LENGTH_LONG)
									.show();
						}

					}

				}
			});
			// Is not owner
		} else {
			// You are joined

			if (extraParticipants.contains(extraCurrentLogonName)) {
				join_button = (Button) findViewById(R.id.join_event_button);
				join_button.setText("Leave Event");
				join_button.setVisibility(View.VISIBLE);
				join_button.setOnClickListener(new OnClickListener() {

					@Override
					public void onClick(View v) {
						XMLParser parser = new XMLParser();
						parametersLeave = "eventId=" + extraEventId
								+ "&userId=" + extraCurrentUserId;
						leaveURL = basicLeaveURL + parametersLeave;
						System.out.println(leaveURL);
						String leaveXml = parser.getXmlFromUrl(leaveURL); // getting
																			// XML
						Document doc = parser.getDomElement(leaveXml); // getting
																		// DOM
																		// element
						NodeList xmlElements = doc
								.getElementsByTagName(KEY_EVENT);
						for (int i = 0; i < xmlElements.getLength(); i++) {

							// creating new HashMap
							HashMap<String, String> eventLeavedMap = new HashMap<String, String>();
							Element e = (Element) xmlElements.item(i);
							// adding each child node to HashMap key => value
							leavedId = parser.getValue(e, KEY_LEAVEID);
							eventLeavedMap.put(KEY_LEAVEID, leavedId);
							eventId = parser.getValue(e, KEY_ID);
							eventLeavedMap.put(KEY_ID, eventId);
							// Not leaved event
							if (joinedId.equals("0")) {

								Intent nextScreen = null;
								nextScreen = new Intent(EventActivity.this,
										MainActivity.class);
								nextScreen.putExtra("userId", ""
										+ extraCurrentUserId);
								nextScreen.putExtra("currentLogonName",
										extraCurrentLogonName);
								startActivityForResult(nextScreen, 0);

								finish();
								join_button.setText("Leave Event");
								Toast.makeText(getApplicationContext(),
										"Event not leaved.", Toast.LENGTH_LONG)
										.show();
								// Leaved event
							} else {
								Intent nextScreen = null;
								nextScreen = new Intent(EventActivity.this,
										MainActivity.class);
								nextScreen.putExtra("userId", ""
										+ extraCurrentUserId);
								nextScreen.putExtra("currentLogonName",
										extraCurrentLogonName);
								startActivityForResult(nextScreen, 0);

								finish();

								Toast.makeText(getApplicationContext(),
										"Event leaved.", Toast.LENGTH_LONG)
										.show();
							}
						}

					}
				});

			} else { // You are not joined
				join_button = (Button) findViewById(R.id.join_event_button);
				join_button.setVisibility(View.VISIBLE);
				join_button.setOnClickListener(new OnClickListener() {

					@Override
					public void onClick(View v) {
						XMLParser parser = new XMLParser();
						parametersJoin = "eventId=" + extraEventId + "&userId="
								+ extraCurrentUserId;
						joinURL = basicJoinURL + parametersJoin;
						System.out.println(joinURL);
						String joinXml = parser.getXmlFromUrl(joinURL); // getting
																		// XML
						System.out.println(joinXml);
						Document doc = parser.getDomElement(joinXml); // getting
																		// DOM
																		// element

						NodeList xmlElements = doc
								.getElementsByTagName(KEY_EVENT);
						for (int i = 0; i < xmlElements.getLength(); i++) {

							// creating new HashMap
							HashMap<String, String> eventJoinedMap = new HashMap<String, String>();
							Element e = (Element) xmlElements.item(i);
							// adding each child node to HashMap key => value
							joinedId = parser.getValue(e, KEY_JOINED);
							eventJoinedMap.put(KEY_JOINED, joinedId);
							eventId = parser.getValue(e, KEY_ID);
							eventJoinedMap.put(KEY_ID, eventId);
							// Joining event
							if (joinedId.equals("1") && free != 0) {

								Intent nextScreen = null;
								nextScreen = new Intent(EventActivity.this,
										MainActivity.class);
								nextScreen.putExtra("userId", ""
										+ extraCurrentUserId);
								nextScreen.putExtra("currentLogonName",
										extraCurrentLogonName);
								startActivityForResult(nextScreen, 0);

								finish();
								join_button.setText("Leave Event");
								Toast.makeText(getApplicationContext(),
										"Event joined.", Toast.LENGTH_LONG)
										.show();
								// Not joining event
							} else {
								Intent nextScreen = null;
								nextScreen = new Intent(EventActivity.this,
										MainActivity.class);
								nextScreen.putExtra("userId", ""
										+ extraCurrentUserId);
								nextScreen.putExtra("currentLogonName",
										extraCurrentLogonName);
								startActivityForResult(nextScreen, 0);

								finish();

								Toast.makeText(getApplicationContext(),
										"Event not joined.", Toast.LENGTH_LONG)
										.show();
							}
						}

					}
				});
			}

			edit_button = (Button) findViewById(R.id.edit_event_button);
			edit_button.setVisibility(View.INVISIBLE);
			cancel_button = (Button) findViewById(R.id.cancel_event_button);
			cancel_button.setVisibility(View.INVISIBLE);
		}

	}

	@Override
	public boolean onCreateOptionsMenu(Menu menu) {
		// Inflate the menu; this adds items to the action bar if it is present.
		getMenuInflater().inflate(R.menu.activity_event, menu);
		return true;
	}

	@Override
	public void onClick(View arg0) {
		// TODO Auto-generated method stub

	}

}
