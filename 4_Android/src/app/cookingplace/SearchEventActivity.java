package app.cookingplace;

import java.util.ArrayList;
import java.util.HashMap;

import org.w3c.dom.Document;
import org.w3c.dom.Element;
import org.w3c.dom.NodeList;

import com.google.android.gms.maps.GoogleMap.OnInfoWindowClickListener;
import com.google.android.gms.maps.model.Marker;

import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.view.Menu;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.AdapterView;
import android.widget.AdapterView.OnItemClickListener;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ListView;
import android.widget.TextView;
import android.widget.Toast;

public class SearchEventActivity extends Activity implements OnClickListener {

	private Button search;
	private ListView searchListview;
	private EditText searchEditText;
	private ArrayList<String> listItems = new ArrayList<String>();
	private ArrayAdapter<String> adapter;
	static final String basicURLAll = "http://sfsuswe.com/~f12g22/web/php/SearchEvent.php?type=all&zipcode=&sessionUser=";
	String URL = "";
	String extraCurrentUserId = "";
	String extraCurrentLogonName = "";

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

	private ArrayList<HashMap<String, String>> events = new ArrayList<HashMap<String, String>>();
	private ArrayList<HashMap<String, String>> searchedEvents = new ArrayList<HashMap<String, String>>();

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_search_event);
		Bundle extras = getIntent().getExtras();
		extraCurrentUserId = extras.getString("userId");
		extraCurrentLogonName = extras.getString("currentLogonName");
		getAllEventsFromXml();
		search = (Button) findViewById(R.id.serachByZip_button);
		search.setOnClickListener(this);
		searchListview = (ListView) findViewById(R.id.serach_listView);
		searchEditText = (EditText) findViewById(R.id.seachByZip_editText);
		for (int i = 0; i < events.size(); i++) {
			listItems.add(events.get(i).get(KEY_TITLE) + " "
					+ events.get(i).get(KEY_ZIP));
		}
		adapter = new ArrayAdapter<String>(this,
				android.R.layout.simple_list_item_1, listItems);

		searchListview.setAdapter(adapter);

		refreshOnItemClickListener();
	}

	public void refreshListView(String zip) {
		listItems.clear();
		for (int i = 0; i < events.size(); i++) {
			if (events.get(i).get(KEY_ZIP).contains(zip)) {
				searchedEvents.add(events.get(i));
			}
		}

		for (int i = 0; i < searchedEvents.size(); i++) {
			if (searchedEvents.get(i).get(KEY_ZIP).contains(zip)) {
				listItems.add(searchedEvents.get(i).get(KEY_TITLE) + " "
						+ searchedEvents.get(i).get(KEY_ZIP));
			}
		}
		refreshOnItemClickListener();
		adapter.notifyDataSetChanged();

	}

	public void refreshOnItemClickListener() {
		searchListview.setOnItemClickListener(new OnItemClickListener() {

			@Override
			public void onItemClick(AdapterView<?> parent, View view,
					int position, long id) {

				Toast.makeText(getApplicationContext(),
						"Item fired. " + searchedEvents.get(position).get(KEY_ZIP),
						Toast.LENGTH_LONG).show();
			}
		});
	}

	@Override
	public void onClick(View v) {
		refreshListView(searchEditText.getText().toString());
	}

	@Override
	public boolean onCreateOptionsMenu(Menu menu) {
		// Inflate the menu; this adds items to the action bar if it is present.
		getMenuInflater().inflate(R.menu.activity_search_event, menu);
		return true;
	}

	private void getAllEventsFromXml() {

		XMLParser parser = new XMLParser();
		URL = basicURLAll + extraCurrentUserId;
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
				if (currentNumberOfParticipants.equals("")) {
					currentNumberOfParticipants = ""
							+ participantNodes.getLength() + 1;
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
			// Add the current event to the events Array
			events.add(eventMap);

			participants = "Gäste: ";
		}
	}
}
