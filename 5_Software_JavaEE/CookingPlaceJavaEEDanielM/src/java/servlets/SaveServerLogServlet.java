/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package servlets;

import java.io.IOException;
import java.io.PrintWriter;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.annotation.Resource;
import javax.jms.ConnectionFactory;
import javax.jms.JMSException;
import javax.jms.Message;
import javax.jms.MessageProducer;
import javax.jms.Queue;
import javax.jms.QueueConnection;
import javax.jms.QueueSender;
import javax.jms.QueueSession;
import javax.jms.Session;
import javax.jms.TextMessage;
import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

/**
 *
 * @author King Luy
 */
@WebServlet(name = "SaveServerLogServlet", urlPatterns = {"/SaveServerLogServlet"})
public class SaveServerLogServlet extends HttpServlet {

    @Resource(mappedName = "jms/SaveServerLog")
    private Queue saveServerLog;
    @Resource(mappedName = "jms/SaveServerLogFactory")
    private ConnectionFactory saveServerLogFactory;
    static final String URL = "jdbc:mysql://sfsuswe.com:3306/student_f12g22?zeroDateTimeBehavior=convertToNull";
    static final String USER = "f12g22";
    static final String PASS = "cookingdb";
    Connection con = null;
    Statement stmt = null;
    ResultSet rs = null;

    /**
     * Processes requests for both HTTP
     * <code>GET</code> and
     * <code>POST</code> methods.
     *
     * @param request servlet request
     * @param response servlet response
     * @throws ServletException if a servlet-specific error occurs
     * @throws IOException if an I/O error occurs
     */
    protected void processRequest(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException, ClassNotFoundException, InstantiationException, IllegalAccessException, SQLException {
        response.setContentType("text/html;charset=UTF-8");
        QueueConnection queueConnection = null;
        PrintWriter out = response.getWriter();
        try {
            Class.forName("com.mysql.jdbc.Driver");
            con = (Connection) DriverManager.getConnection(URL, USER, PASS);
            if (!con.isClosed()) {
                System.out.println("DB Verbindung hergestellt!");
                stmt = con.createStatement();
                rs = stmt.executeQuery("SELECT * FROM recipe");
            }
            queueConnection = (QueueConnection) saveServerLogFactory.createConnection();
            queueConnection.start();
            QueueSession queueSession = queueConnection.createQueueSession(false,
                    Session.AUTO_ACKNOWLEDGE);
            QueueSender sender = queueSession.createSender(saveServerLog);
            String serverText = "This is the log:\n";
            TextMessage msg = queueSession.createTextMessage();

//            if(rs.next()){
//                serverText += rs.getString(3);
//            }
//            System.out.println("Servertext: " + serverText);

            while (rs.next()) {
                serverText += "------------------------------------------------"
                        + "-------------------------------------------------\r\n";
                serverText += "UserID: " + rs.getString(2) + "\r\n";
                serverText += "Recipe Title: " + rs.getString(3) + "\r\n";
                serverText += "Abstract: " + rs.getString(8) + "\r\n";
                serverText += "Preparation: " + rs.getString(9) + "\r\n";
                serverText += "Cooking Time: " + rs.getString(10) + "\r\n";
                serverText += "Serving: " + rs.getString(11) + "\r\n";
                serverText += "Voting: " + rs.getString(6) + "\r\n";
                serverText += "Total Votes: " + rs.getString(7) + "\r\n";
            }
            msg.setStringProperty("serverText", serverText);
            sender.send(msg);

            out.println("<html>");
            out.println("<head>");
            out.println("<title>Servlet to save Server Log</title>");
            out.println("</head>");
            out.println("<body>");
            out.println("<h1>File is saved to C:/Users/King Luy/Desktop/Server Recipe Log.txt</h1>");
            out.println("</body>");
            out.println("</html>");

        } catch (JMSException e) {
            throw new RuntimeException(e);
        } catch (ClassNotFoundException e) {
            throw new RuntimeException(e);
        } finally {
            out.close();
            try {
                if (queueConnection != null) {
                    queueConnection.close();
                }
            } catch (JMSException e) { //ignore
            }
        }
    }

    // <editor-fold defaultstate="collapsed" desc="HttpServlet methods. Click on the + sign on the left to edit the code.">
    /**
     * Handles the HTTP
     * <code>GET</code> method.
     *
     * @param request servlet request
     * @param response servlet response
     * @throws ServletException if a servlet-specific error occurs
     * @throws IOException if an I/O error occurs
     */
    @Override
    protected void doGet(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
        try {
            processRequest(request, response);
        } catch (ClassNotFoundException ex) {
            Logger.getLogger(SaveServerLogServlet.class.getName()).log(Level.SEVERE, null, ex);
        } catch (InstantiationException ex) {
            Logger.getLogger(SaveServerLogServlet.class.getName()).log(Level.SEVERE, null, ex);
        } catch (IllegalAccessException ex) {
            Logger.getLogger(SaveServerLogServlet.class.getName()).log(Level.SEVERE, null, ex);
        } catch (SQLException ex) {
            Logger.getLogger(SaveServerLogServlet.class.getName()).log(Level.SEVERE, null, ex);
        }
    }

    /**
     * Handles the HTTP
     * <code>POST</code> method.
     *
     * @param request servlet request
     * @param response servlet response
     * @throws ServletException if a servlet-specific error occurs
     * @throws IOException if an I/O error occurs
     */
    @Override
    protected void doPost(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
        try {
            processRequest(request, response);
        } catch (ClassNotFoundException ex) {
            Logger.getLogger(SaveServerLogServlet.class.getName()).log(Level.SEVERE, null, ex);
        } catch (InstantiationException ex) {
            Logger.getLogger(SaveServerLogServlet.class.getName()).log(Level.SEVERE, null, ex);
        } catch (IllegalAccessException ex) {
            Logger.getLogger(SaveServerLogServlet.class.getName()).log(Level.SEVERE, null, ex);
        } catch (SQLException ex) {
            Logger.getLogger(SaveServerLogServlet.class.getName()).log(Level.SEVERE, null, ex);
        }
    }

    /**
     * Returns a short description of the servlet.
     *
     * @return a String containing servlet description
     */
    @Override
    public String getServletInfo() {
        return "Short description";
    }// </editor-fold>

    private Message createJMSMessageForjmsSaveServerLog(Session session, Object messageData) throws JMSException {
        // TODO create and populate message to send
        TextMessage tm = session.createTextMessage();
        tm.setText(messageData.toString());
        return tm;
    }

    private void sendJMSMessageToSaveServerLog(Object messageData) throws JMSException {
        javax.jms.Connection connection = null;
        Session session = null;
        try {
            connection = saveServerLogFactory.createConnection();
            session = connection.createSession(false, Session.AUTO_ACKNOWLEDGE);
            MessageProducer messageProducer = session.createProducer(saveServerLog);
            messageProducer.send(createJMSMessageForjmsSaveServerLog(session, messageData));
        } finally {
            if (session != null) {
                try {
                    session.close();
                } catch (JMSException e) {
                    Logger.getLogger(this.getClass().getName()).log(Level.WARNING, "Cannot close session", e);
                }
            }
            if (connection != null) {
                connection.close();
            }
        }
    }
}
