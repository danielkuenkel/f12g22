package app.cookingplace;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;

import org.w3c.dom.Document;
import org.w3c.dom.Element;
import org.w3c.dom.NodeList;

import android.app.AlertDialog;
import android.content.Intent;
import android.location.Address;
import android.location.Geocoder;
import android.os.Bundle;
import android.view.Menu;
import android.view.View;
import android.widget.CheckBox;
import android.widget.Toast;

import com.google.android.gms.maps.CameraUpdateFactory;
import com.google.android.gms.maps.GoogleMap;
import com.google.android.gms.maps.GoogleMap.OnInfoWindowClickListener;
import com.google.android.gms.maps.SupportMapFragment;
import com.google.android.gms.maps.UiSettings;
import com.google.android.gms.maps.model.LatLng;
import com.google.android.gms.maps.model.Marker;
import com.google.android.gms.maps.model.MarkerOptions;

public class MapsActivity extends android.support.v4.app.FragmentActivity {

	static final String basicURL = "http://sfsuswe.com/~f12g22/web/php/SearchEvent.php?type=all&zipcode=&sessionUser=";
	String URL = "";

	static final String KEY_EVENT = "event"; // parent node
	static final String KEY_ID = "id";
	String eventId = "";
	static final String KEY_TITLE = "title";
	String title = "";
	static final String KEY_USERID = "user_id";
	String userId = "";
	static final String KEY_LOGONNAME = "logonName";
	String logonName = "";
	static final String KEY_ISOWNER = "isOwner";
	String isOwner = "";
	static final String KEY_ABSTRACT = "abstract";
	String abstractString = "";
	static final String KEY_MAXPARTICIPANTS = "maxParticipants";
	String maxParticipants = "";
	static final String KEY_COST = "cost";
	String cost = "";
	static final String KEY_ZIP = "zip";
	String zip = "";
	static final String KEY_STREET = "street";
	String street = "";
	static final String KEY_HOUSENUMBER = "houseNumber";
	String houseNumber = "";
	static final String KEY_TIMESTAMP = "timestamp";
	String timestamp = "";
	private final String KEY_PARTICIPANTS = "participants";
	ArrayList<String> participantsList = null;
	String participants = "Gäste: ";
	private final String KEY_PARTICIPANT = "participant";
	String participant = "";
	private final String KEY_PARTICIPANTNAME = "name";
	String participantName = "";
	static final String KEY_CITY = "city";
	String city = "";
	static final String KEY_ADDRESS = "adress";
	String address = "";
	static final String KEY_LAT = "lat";
	double lat = 0.0;
	static final String KEY_LNG = "lng";
	double lng = 0.0;
	private static final String KEY_CURRENTNUMBEROFPARTICIPANTS = "currentNumberOfParticipants";
	private String currentNumberOfParticipants = "";

	String extraCurrentUserId = "";
	String extraCurrentLogonName = "";

	private ArrayList<HashMap<String, String>> events = new ArrayList<HashMap<String, String>>();
	private HashMap<Marker, HashMap<String, String>> eventMarkerMap = new HashMap<Marker, HashMap<String, String>>();
	private GoogleMap myMap;
	private UiSettings myUiSettings;

	// static final CameraPosition MYHOME = new CameraPosition.Builder()
	// .target(new LatLng(50.661216, 9.763882)).zoom(15.5f).bearing(0)
	// .tilt(25).build();
	//
	static final LatLng DANIELHOME = new LatLng(50.661216, 9.763882);

	@Override
	protected void onStart() {
		super.onStart();
		getAllEventsFromXml();
	}

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_maps);
		Bundle extras = getIntent().getExtras();
		extraCurrentUserId = extras.getString("userId");
		extraCurrentLogonName = extras.getString("currentLogonName");
		setUpMapIfNeeded();
		setLocation();
	}

	@Override
	public boolean onCreateOptionsMenu(Menu menu) {
		// Inflate the menu; this adds items to the action bar if it is present.
		getMenuInflater().inflate(R.menu.activity_maps, menu);
		return true;
	}
	
	private void getAllEventsFromXml() {

		XMLParser parser = new XMLParser();
		URL = basicURL + extraCurrentUserId;
		String xml = parser.getXmlFromUrl(URL); // getting XML
		Document doc = parser.getDomElement(xml); // getting DOM element

		NodeList xmlElements = doc.getElementsByTagName(KEY_EVENT);

		// looping through all item nodes <KEY_EVENT>
		for (int i = 0; i < xmlElements.getLength(); i++) {
			// creating new HashMap
			HashMap<String, String> eventMap = new HashMap<String, String>();
			Element e = (Element) xmlElements.item(i);
			// adding each child node to HashMap key => value
			eventId = parser.getValue(e, KEY_ID);
			eventMap.put(KEY_ID, eventId);
			title = parser.getValue(e, KEY_TITLE);
			eventMap.put(KEY_TITLE, title);
			userId = parser.getValue(e, KEY_USERID);
			eventMap.put(KEY_USERID, userId);
			logonName = parser.getValue(e, KEY_LOGONNAME);
			eventMap.put(KEY_LOGONNAME, logonName);
			isOwner = parser.getValue(e, KEY_ISOWNER);
			eventMap.put(KEY_ISOWNER, isOwner);
			abstractString = parser.getValue(e, KEY_ABSTRACT);
			eventMap.put(KEY_ABSTRACT, abstractString);
			maxParticipants = parser.getValue(e, KEY_MAXPARTICIPANTS);
			eventMap.put(KEY_MAXPARTICIPANTS, maxParticipants);
			cost = parser.getValue(e, KEY_COST);
			eventMap.put(KEY_COST, cost);
			zip = parser.getValue(e, KEY_ZIP);
			eventMap.put(KEY_ZIP, zip);
			street = parser.getValue(e, KEY_STREET);
			eventMap.put(KEY_STREET, street);
			houseNumber = parser.getValue(e, KEY_HOUSENUMBER);
			eventMap.put(KEY_HOUSENUMBER, houseNumber);
			city = parser.getValue(e, KEY_CITY);
			eventMap.put(KEY_CITY, city);
			timestamp = parser.getValue(e, KEY_TIMESTAMP);
			eventMap.put(KEY_TIMESTAMP, timestamp);

			NodeList participantsNodes = e
					.getElementsByTagName(KEY_PARTICIPANTS);
			for (int j = 0; j < participantsNodes.getLength(); j++) {
				Element ep = (Element) participantsNodes.item(j);
				NodeList participantNodes = ep
						.getElementsByTagName(KEY_PARTICIPANT);
				if (currentNumberOfParticipants.equals("")){
					currentNumberOfParticipants = "" + participantNodes.getLength()
						+ 1;
				}
				
				for (int k = 0; k < participantNodes.getLength(); k++) {
					Element participant = (Element) participantNodes.item(k);
					parser.getValue(participant, KEY_PARTICIPANTNAME);
					// participantsList.add(parser.getValue(participant,
					// KEY_PARTICIPANTNAME));
					participants += parser.getValue(participant,
							KEY_PARTICIPANTNAME) + " ";
				}
			}
			eventMap.put(KEY_CURRENTNUMBEROFPARTICIPANTS,
					currentNumberOfParticipants);
			// Set all participants to participants String
			eventMap.put(KEY_PARTICIPANTS, participants);

			address = zip + " " + city + " " + street + " " + houseNumber;
			eventMap.put(KEY_ADDRESS, address);
			// Calculate the address string in Lat and Lng
			calculateGeocode(address);
			eventMap.put(KEY_LAT, "" + lat);
			eventMap.put(KEY_LNG, "" + lng);
			// Add the current event to the events Array
			events.add(eventMap);
			// Add the marker to the map
			Marker marker = addMarkerToMap(lat, lng, title, abstractString);
			eventMarkerMap.put(marker, eventMap);

			myMap.setOnInfoWindowClickListener(new OnInfoWindowClickListener() {

				

				@Override
				public void onInfoWindowClick(Marker marker) {
					HashMap<String, String> eventInfos = eventMarkerMap
							.get(marker);
					Intent nextScreen = new Intent(MapsActivity.this,
							EventActivity.class);
					nextScreen.putExtra("currentUserId", extraCurrentUserId);
					nextScreen.putExtra("eventId", eventInfos.get(KEY_ID));
					nextScreen.putExtra("eventTitle", eventInfos.get(KEY_TITLE));
					nextScreen.putExtra("userId", eventInfos.get(KEY_USERID));
					nextScreen.putExtra("logonName",
							eventInfos.get(KEY_LOGONNAME));
					nextScreen.putExtra("isOwner", eventInfos.get(KEY_ISOWNER));
					nextScreen.putExtra("abstract",
							eventInfos.get(KEY_ABSTRACT));
					nextScreen.putExtra("maxParticipants",
							eventInfos.get(KEY_MAXPARTICIPANTS));
					nextScreen.putExtra("cost", eventInfos.get(KEY_COST));
					nextScreen.putExtra("street", eventInfos.get(KEY_STREET));
					nextScreen.putExtra("houseNumber",
							eventInfos.get(KEY_HOUSENUMBER));
					nextScreen.putExtra("zip", eventInfos.get(KEY_ZIP));
					nextScreen.putExtra("city", eventInfos.get(KEY_CITY));
					nextScreen.putExtra("timestamp",
							eventInfos.get(KEY_TIMESTAMP));
					nextScreen.putExtra("participants",
							eventInfos.get(KEY_PARTICIPANTS));
					nextScreen.putExtra("currentNumberOfParticipants",
							eventInfos.get(KEY_CURRENTNUMBEROFPARTICIPANTS));
					nextScreen.putExtra("currentLogonName", extraCurrentLogonName);
					
					startActivityForResult(nextScreen, 0);
					finish();
				}
			});
			participants = "Gäste: ";
		}
	}

	private void calculateGeocode(String addressInput) {
		Geocoder gc = new Geocoder(this); // create new geocoder instance
		try {
			List<Address> foundAdresses = gc.getFromLocationName(addressInput,
					5); // Search addresses

			if (foundAdresses.size() == 0) { // if no address found, display an
												// error
				new AlertDialog.Builder(this)
						.setMessage("No Eventaddress found to calculate.")
						.setNeutralButton(R.string.error_ok, null).show();
			} else { // else display address on map
				for (int j = 0; j < foundAdresses.size(); j++) {
					// Save results as Longitude and Latitude
					Address x = foundAdresses.get(j);
					lat = x.getLatitude();
					lng = x.getLongitude();
				}
			}
		} catch (Exception e) {
			new AlertDialog.Builder(this)
					.setMessage("Error with Address calculation.")
					.setNeutralButton(R.string.error_ok, null).show();
		}
	}

	private Marker addMarkerToMap(double lat, double lng, String eventTitle,
			String eventDiscribtion) {
		Marker currentMarker = myMap.addMarker(new MarkerOptions()
				.position(new LatLng(lat, lng)).title(eventTitle)
				.snippet(eventDiscribtion));
		return currentMarker;
	}

	private void setLocation() {
		myMap.animateCamera(CameraUpdateFactory.newLatLngZoom(DANIELHOME, 5),
				3000, null);
	}

	@Override
	protected void onResume() {
		super.onResume();
		setUpMapIfNeeded();
		getAllEventsFromXml();
	}

	private void setUpMapIfNeeded() {
		// Do a null check to confirm that we have not already instantiated the
		if (myMap == null) {
			// Try to obtain the map from the SupportMapFragment.
			myMap = ((SupportMapFragment) getSupportFragmentManager()
					.findFragmentById(R.id.map)).getMap();
			System.out.println(myMap);
			myMap.clear();
			System.out.println(myMap);
			// Check if we were successful in obtaining the map.
			if (myMap != null) {
				setUpMap();
				myMap.clear();
			}
		}
	}

	private void setUpMap() {
		myMap.setMyLocationEnabled(true);
		myUiSettings = myMap.getUiSettings();
	}

	/**
	 * Checks if the map is ready (which depends on whether the Google Play
	 * services APK is available. This should be called prior to calling any
	 * methods on GoogleMap.
	 */
	private boolean checkReady() {
		if (myMap == null) {
			Toast.makeText(this, R.string.map_not_ready, Toast.LENGTH_SHORT)
					.show();
			return false;
		}
		return true;
	}

	public void setZoomButtonsEnabled(View v) {
		if (!checkReady()) {
			return;
		}
		// Enables/disables the zoom controls (+/- buttons in the bottom right
		// of the map).
		myUiSettings.setZoomControlsEnabled(((CheckBox) v).isChecked());
	}

	public void setMyLocationButtonEnabled(View v) {
		if (!checkReady()) {
			return;
		}
		// Enables/disables the my location button (this DOES NOT enable/disable
		// the my location
		// dot/chevron on the map). The my location button will never appear if
		// the my location
		// layer is not enabled.
		myUiSettings.setMyLocationButtonEnabled(((CheckBox) v).isChecked());
	}

	public void setMyLocationLayerEnabled(View v) {
		if (!checkReady()) {
			return;
		}
		// Enables/disables the my location layer (i.e., the dot/chevron on the
		// map). If enabled, it
		// will also cause the my location button to show (if it is enabled); if
		// disabled, the my
		// location button will never show.
		myMap.setMyLocationEnabled(((CheckBox) v).isChecked());
	}

	public void setScrollGesturesEnabled(View v) {
		if (!checkReady()) {
			return;
		}
		// Enables/disables scroll gestures (i.e. panning the map).
		myUiSettings.setScrollGesturesEnabled(((CheckBox) v).isChecked());
	}

	public void setZoomGesturesEnabled(View v) {
		if (!checkReady()) {
			return;
		}
		// Enables/disables zoom gestures (i.e., double tap, pinch & stretch).
		myUiSettings.setZoomGesturesEnabled(((CheckBox) v).isChecked());
	}

	public void setTiltGesturesEnabled(View v) {
		if (!checkReady()) {
			return;
		}
		// Enables/disables tilt gestures.
		myUiSettings.setTiltGesturesEnabled(((CheckBox) v).isChecked());
	}

	public void setRotateGesturesEnabled(View v) {
		if (!checkReady()) {
			return;
		}
		// Enables/disables rotate gestures.
		myUiSettings.setRotateGesturesEnabled(((CheckBox) v).isChecked());
	}

}
