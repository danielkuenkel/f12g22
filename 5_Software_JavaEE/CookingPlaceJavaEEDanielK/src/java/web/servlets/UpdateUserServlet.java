/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package web.servlets;

import java.io.IOException;
import java.io.PrintWriter;
import java.math.BigInteger;
import java.security.MessageDigest;
import java.security.NoSuchAlgorithmException;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.ResultSet;
import java.sql.Statement;
import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

/**
 *
 * @author daniel-kuenkel
 */
@WebServlet(name = "UpdateUserServlet", urlPatterns = {"/UpdateUserServlet"})
public class UpdateUserServlet extends HttpServlet {

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
    static final String DBURL = "jdbc:mysql://sfsuswe.com:3306/student_f12g22?zeroDateTimeBehavior=convertToNull";
    static final String DBUSER = "f12g22";
    static final String DBPASS = "cookingdb";

    protected void processRequest(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
        
        response.setHeader("Access-Control-Allow-Origin", "*");
        response.setContentType("text/xml");
        PrintWriter out = response.getWriter();

        Connection con;

        out.println("<?xml version=\"1.0\"?>");
        out.println("<user>");

        try {
            String forename = request.getParameter("forename");
            String surname = request.getParameter("surname");
            String password = request.getParameter("password");
            String street = request.getParameter("street");
            String housenumber = request.getParameter("housenumber");
            String zip = request.getParameter("zip");
            String city = request.getParameter("city");
            String phone = request.getParameter("phone");
            String userId = request.getParameter("userId");

            Class.forName("com.mysql.jdbc.Driver").newInstance();

            con = DriverManager.getConnection(DBURL, DBUSER, DBPASS);
            System.out.println(con);
            if (!con.isClosed()) {
                System.out.println("connect to DB!");
                Statement statement = con.createStatement();
                String query = "";
                if (password == "") {
                    System.out.println("update without pass");
                    query = "UPDATE user SET firstname='" + forename
                            + "', lastname='" + surname
                            + "', street='" + street
                            + "', house_number='" + housenumber
                            + "', zipcode='" + zip
                            + "', city='" + city
                            + "', phone_number='" + phone
                            + "' WHERE user_id = " + userId + ";";
                } else {
                    System.out.println("update with pass");
                    String md5Hash = asString(password.getBytes());

                    query = "UPDATE user SET firstname='" + forename
                            + "', lastname='" + surname
                            + "', street='" + street
                            + "', house_number='" + housenumber
                            + "', zipcode='" + zip
                            + "', city='" + city
                            + "', phone_number='" + phone
                            + "', password='" + md5Hash
                            + "' WHERE user_id = " + userId + ";";
                }


                int resultSet = statement.executeUpdate(query);
                if(resultSet == 1)
                {
                    out.println("<success>true</success>");
                    out.println("<forename>" + forename + "</forename>");
                    out.println("<surname>" + surname + "</surname>");
                    out.println("<street>" + street + "</street>");
                    out.println("<housenumber>" + housenumber + "</housenumber>");
                    out.println("<zip>" + zip + "</zip>");
                    out.println("<city>" + city + "</city>");
                    out.println("<phone>" + phone + "</phone>");
                }
                else
                {
                    out.println("<success>false</success>");
                }
            } else {
                out.println("<success>false</success>");
                System.out.println("could not connect to DB");
            }
        } catch (Exception e) {
            out.println("<success>false</success>");
            System.out.println("exception: " + e);
        }
        out.println("</user>");
    }

    public static String asString(byte[] data) {
        String result = null;

        try {
            MessageDigest md = MessageDigest.getInstance("MD5");
            result = new BigInteger(1, md.digest(data)).toString(16);
        } catch (NoSuchAlgorithmException e) {
            e.printStackTrace();
        }

        return result;
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
        processRequest(request, response);
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
        processRequest(request, response);
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
}
