/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package beans;

import java.io.File;
import java.io.FileOutputStream;
import java.io.PrintWriter;
import java.util.logging.Logger;
import javax.ejb.ActivationConfigProperty;
import javax.ejb.MessageDriven;
import javax.jms.JMSException;
import javax.jms.Message;
import javax.jms.MessageListener;
import javax.jms.ObjectMessage;

/**
 *
 * @author King Luy
 */
@MessageDriven(mappedName = "jms/SaveServerLog", activationConfig = {
    @ActivationConfigProperty(propertyName = "acknowledgeMode", propertyValue = "Auto-acknowledge"),
    @ActivationConfigProperty(propertyName = "destinationType", propertyValue = "javax.jms.Queue")
})
public class SaveServerLogMDB implements MessageListener {

    Logger logger = Logger.getLogger("test");

    public SaveServerLogMDB() {
    }

    @Override
    public void onMessage(Message message) {
        try {
            String serverText = message.getStringProperty("serverText");
            File file = new File("C:/Users/King Luy/Desktop/Server Recipe Log.txt");
            if(file.exists()){
                file.delete();
            }
            PrintWriter out = new PrintWriter(new FileOutputStream(new File("C:/Users/King Luy/Desktop/Server Recipe Log.txt"), true));
            out.println(serverText);
            out.close();
        } catch (JMSException e) {
            throw new RuntimeException(e);
        } catch (Exception e) {
            System.err.println(e);
        }
    }
}
