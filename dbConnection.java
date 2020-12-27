import java.io.*;
import java.sql.*;

public class dbConnection {
    public static void main(String[] args) {

        // information for connecting to db
        String username = "root";
        String password = "";
        //String username = "nogay.evirgen";
        //String password = "GmDj7KCS";

        // idk why but if I use the string "jdbc:mysql://localhost:3306/hw4" , I'll get an error about time zone etc. Apparently, It's a bug about mySQL and timezones. I don't know why the line
        // below solves the problem, but it solves.
        String jdbc = "jdbc:mysql://localhost:3306/dbproject?useUnicode=true&useJDBCCompliantTimezoneShift=true&useLegacyDatetimeCode=false&serverTimezone=UTC";
        //String jdbc = "jdbc:mysql://dijkstra.ug.bcc.bilkent.edu.tr:3306/nogay_evirgen";


        try {
            Class.forName("com.mysql.cj.jdbc.Driver");
            System.out.println("connecting to database...\n");
            Connection conn = DriverManager.getConnection(jdbc,username,password);
            System.out.println("connecting to database was a success.\n");


            Statement stmt = conn.createStatement();

            System.out.println("Checking whether table's exists or not.\n");
            System.out.println("If they exist, they will be removed and recreated.\n");
            stmt.execute("SET FOREIGN_KEY_CHECKS=0; -- to disable them");
            stmt.execute("DROP TABLE IF EXISTS person, relationship, friendship, friendshipmessages, community, joins, communitymessages, systemrequirements, game, " +
                    "gamemod, develop, has, download, shophistory, renew, refundhistory, request, personreview, review, curator, curatorreview, publish, follow," +
                    "suggestgamelist, gamelistcontain, wishlist, publisher, developer, tester, bugreport, debug, updateGame, ask, publishgame, most_followed_curators, trigger_credits");
            stmt.execute("SET FOREIGN_KEY_CHECKS=1; -- to disable them");

            stmt.execute("DROP VIEW IF EXISTS installed_games, most_followed_curators, top_rated_games");

            stmt.execute("DROP PROCEDURE IF EXISTS GameIdToGameName");
            stmt.execute("DROP PROCEDURE IF EXISTS GameNameToGameId");


            boolean LOAD = true;

            if(LOAD){
                ScriptRunner runner = new ScriptRunner(conn, false, false);
                String file = "D:\\cs353programmingAssigment\\/dbproject.sql";
                runner.runScript(new BufferedReader(new FileReader(file)));

            }
            else {


                //PERSON
                String personDefinition = "CREATE TABLE person " +
                        "(person_id INT AUTO_INCREMENT, " +
                        "nick_name VARCHAR(32) NOT NULL UNIQUE, " +
                        "email VARCHAR(32) NOT NULL UNIQUE, " +
                        "password VARCHAR(32) NOT NULL, " +
                        "credits FLOAT DEFAULT 100 CHECK(credits >= 0), " +
                        "person_name VARCHAR(32) NOT NULL, " +
                        "person_surname VARCHAR(32) NOT NULL, " +
                        "PRIMARY KEY(person_id))";
                System.out.println("Creating person table.");
                stmt.execute(personDefinition);
                System.out.println("Person table is created successfully.\n");


                //FRIENDSHIP
                String friendshipDefinition = "CREATE TABLE friendship " +
                        "(friendship_id INT AUTO_INCREMENT, " +
                        "PRIMARY KEY(friendship_id))";
                System.out.println("Creating friendship table.");
                stmt.execute(friendshipDefinition);
                System.out.println("Friendship table is created successfully.\n");

                //RELATIONSHIP
                String relationshipDefinition = "CREATE TABLE relationship " +
                        "(friendship_id INT, " +
                        "person_id1 INT, " +
                        "person_id2 INT, " +
                        "relationship_status ENUM('Waiting for Approval', 'Declined', 'Accepted') DEFAULT 'Waiting for Approval', " +
                        "relationship_msg VARCHAR(300)," +
                        "PRIMARY KEY(friendship_id), " +
                        "UNIQUE(person_id1, person_id2), " +
                        "FOREIGN KEY (friendship_id) REFERENCES friendship(friendship_id), " +
                        "FOREIGN KEY (person_id1) REFERENCES person(person_id), " +
                        "FOREIGN KEY (person_id2) REFERENCES person(person_id))";
                System.out.println("Creating relationship table.");
                stmt.execute(relationshipDefinition);
                System.out.println("Relationship table is created successfully.\n");

                //FRIENDSHIPMESSAGES
                String friendshipmessagesDefinition = "CREATE TABLE friendshipmessages " +
                        "(f_msg_id INT AUTO_INCREMENT, " +
                        "f_msg_content VARCHAR(100), " +
                        "friendship_id INT, " +
                        "PRIMARY KEY(f_msg_id), " +
                        "FOREIGN KEY (friendship_id) REFERENCES friendship(friendship_id))";
                System.out.println("Creating FriendshipMessages table.");
                stmt.execute(friendshipmessagesDefinition);
                System.out.println("FriendshipMessages table is created successfully.\n");

                //COMMUNITY
                String communityDefinition = "CREATE TABLE community " +
                        "(community_id INT AUTO_INCREMENT, " +
                        "community_name VARCHAR(32) NOT NULL UNIQUE, " +
                        "PRIMARY KEY(community_id))";
                stmt.execute(communityDefinition);
                System.out.println("Community table is created successfully.\n");

                //JOIN
                String joinsDefinition = "CREATE TABLE joins " +
                        "(person_id INT , " +
                        "community_id INT , " +
                        "PRIMARY KEY(person_id, community_id), " +
                        "FOREIGN KEY (person_id) REFERENCES person(person_id), " +
                        "FOREIGN KEY (community_id) REFERENCES community(community_id))";
                stmt.execute(joinsDefinition);
                System.out.println("Join table is created successfully.\n");

                //COMMUNITY MESSAGES
                String communitymessagesDefinition = "CREATE TABLE communitymessages " +
                        "(c_msg_id INT AUTO_INCREMENT, " +
                        "c_msg_content VARCHAR(100), " +
                        "community_id INT, " +
                        "PRIMARY KEY(c_msg_id))";
                stmt.execute(communitymessagesDefinition);
                System.out.println("CommunityMessages table is created successfully.\n");

                //SYSTEM REQ
                String systemrequirementsDefinition = "CREATE TABLE systemrequirements " +
                        "(req_id INT AUTO_INCREMENT, " +
                        "os VARCHAR(32) NOT NULL, " +
                        "processor VARCHAR(32) NOT NULL, " +
                        "memory VARCHAR(32) NOT NULL, " +
                        "storage VARCHAR(32) NOT NULL, " +
                        "PRIMARY KEY(req_id))";
                stmt.execute(systemrequirementsDefinition);
                System.out.println("SystemRequirements table is created successfully.\n");

                //GAME primary key dikkat et
                String gameDefinition = "CREATE TABLE game " +
                        "(game_id INT AUTO_INCREMENT PRIMARY KEY, " +
                        "game_name VARCHAR(32) NOT NULL UNIQUE, " +
                        "game_price FLOAT NOT NULL CHECK(game_price >= 0), " +
                        "req_id INT, " +
                        "game_desc VARCHAR(250) NOT NULL, " +
                        "game_genre VARCHAR(250) NOT NULL, " +
                        "latest_version_no FLOAT NOT NULL DEFAULT 1.0, " +
                        "FOREIGN KEY (req_id) REFERENCES systemrequirements(req_id))";
                stmt.execute(gameDefinition);
                System.out.println("Game table is created successfully.\n");

                //MOD
                String modDefinition = "CREATE TABLE gamemod " +
                        "(mod_id INT AUTO_INCREMENT, " +
                        "mod_name VARCHAR(32) NOT NULL, " +
                        "mod_desc VARCHAR(120) NOT NULL, " +
                        "PRIMARY KEY(mod_id))";
                stmt.execute(modDefinition);
                System.out.println("Mod table is created successfully.\n");

                //DEVELOP
                String developDefinition = "CREATE TABLE develop " +
                        "(mod_id INT, " +
                        "person_id INT, " +
                        "game_id INT, " +
                        "PRIMARY KEY(mod_id), " +
                        "FOREIGN KEY(mod_id) REFERENCES gamemod(mod_id), " +
                        "FOREIGN KEY(person_id) REFERENCES person(person_id), " +
                        "FOREIGN KEY(game_id) REFERENCES game(game_id))";
                stmt.execute(developDefinition);
                System.out.println("Develop table is successfully created.\n");

                //HAS
                String hasDefinition = "CREATE TABLE has " +
                        "(person_id INT, " +
                        "game_id INT, " +
                        "isInstalled BIT DEFAULT 0, " +
                        "personVersion FLOAT NOT NULL," +
                        "PRIMARY KEY(person_id, game_id), " +
                        "FOREIGN KEY (person_id) REFERENCES person(person_id), " +
                        "FOREIGN KEY (game_id) REFERENCES game(game_id))";
                stmt.execute(hasDefinition);
                System.out.println("Has table is created successfully.\n");

                //DOWNLOAD
                String downloadDefinition = "CREATE TABLE download " +
                        "(person_id INT, " +
                        "game_id INT, " +
                        "mod_id INT, " +
                        "PRIMARY KEY(person_id, mod_id), " +
                        "FOREIGN KEY (person_id) REFERENCES person(person_id), " +
                        "FOREIGN KEY (game_id) REFERENCES game(game_id), " +
                        "FOREIGN KEY (mod_id) REFERENCES gamemod(mod_id))";
                stmt.execute(downloadDefinition);
                System.out.println("Download table is created successfully.\n");

                //SHOP HISTORY
                String shopHistoryDefinition = "CREATE TABLE shophistory " +
                        "(shop_id INT AUTO_INCREMENT, " +
                        "bought_date DATE, " +
                        "bought_price FLOAT, " +
                        "PRIMARY KEY(shop_id))";
                stmt.execute(shopHistoryDefinition);
                System.out.println("ShopHistory table is created successfully.\n");

                //RENEW
                String renewDefinition = "CREATE TABLE renew " +
                        "(shop_id INT, " +
                        "person_id INT, " +
                        "game_id INT, " +
                        "buy_type ENUM('buy', 'gift'), " +
                        "PRIMARY KEY(shop_id), " +
                        "FOREIGN KEY (shop_id) REFERENCES shophistory(shop_id), " +
                        "FOREIGN KEY (person_id) REFERENCES person(person_id), " +
                        "FOREIGN KEY (game_id) REFERENCES game(game_id))";

                stmt.execute(renewDefinition);
                System.out.println("Renew table is created successfully.\n");

                //PUBLISHER
                String publisherDefinition = "CREATE TABLE publisher " +
                        "(publisher_id INT AUTO_INCREMENT, " +
                        "publisher_login_name VARCHAR(32) NOT NULL UNIQUE," +
                        "publisher_name VARCHAR(32) NOT NULL, " +
                        "publisher_email VARCHAR(32) NOT NULL UNIQUE, " +
                        "publisher_password VARCHAR(32) NOT NULL, " +
                        "PRIMARY KEY(publisher_id))";
                stmt.execute(publisherDefinition);
                System.out.println("Publisher table is created successfully.\n");

                //REFUND HISTORY
                String refundhistoryDefinition = "CREATE TABLE refundhistory " +
                        "(refund_id INT AUTO_INCREMENT, " +
                        "shop_id INT, " +
                        "refund_description VARCHAR(120) NOT NULL, " +
                        "refund_approval ENUM('Waiting for Approval', 'Declined', 'Accepted') DEFAULT 'Waiting for Approval', " +
                        "PRIMARY KEY(refund_id), " +
                        "FOREIGN KEY (shop_id) REFERENCES shophistory(shop_id))";
                stmt.execute(refundhistoryDefinition);
                System.out.println("RefundHistory table is created successfully.\n");

                //REQUEST
                String requestDefinition = "CREATE TABLE request " +
                        "(refund_id INT, " +
                        "person_id INT, " +
                        "game_id INT, " +
                        "publisher_id INT, " +
                        "PRIMARY KEY(refund_id), " +
                        "UNIQUE (person_id, game_id)," +
                        "FOREIGN KEY (refund_id) REFERENCES refundhistory(refund_id), " +
                        "FOREIGN KEY(person_id) REFERENCES person(person_id), " +
                        "FOREIGN KEY(publisher_id) REFERENCES publisher(publisher_id), " +
                        "FOREIGN KEY(game_id) REFERENCES game(game_id))";
                stmt.execute(requestDefinition);
                System.out.println("Request table is created successfully.\n");

                //PERSON REVIEW
                String personreviewDefinition = "CREATE TABLE personreview " +
                        "(review_id INT AUTO_INCREMENT, " +
                        "review_text VARCHAR(300) NOT NULL, " +
                        "review_score INT NOT NULL, " +
                        "PRIMARY KEY(review_id))";
                stmt.execute(personreviewDefinition);
                System.out.println("PersonReview table is created successfully.\n");

                //REVIEW
                String reviewDefinition = "CREATE TABLE review " +
                        "(review_id INT, " +
                        "person_id INT, " +
                        "game_id INT, " +
                        "PRIMARY KEY(review_id), " +
                        "UNIQUE(person_id, game_id), " +
                        "FOREIGN KEY (person_id) REFERENCES person(person_id), " +
                        "FOREIGN KEY (game_id) REFERENCES game(game_id), " +
                        "FOREIGN KEY (review_id) REFERENCES personreview(review_id))";
                stmt.execute(reviewDefinition);
                System.out.println("Review table is successfully created.\n");

                //CURATOR
                String curatorDefinition = "CREATE TABLE curator " +
                        "(curator_id INT AUTO_INCREMENT, " +
                        "curator_login_name VARCHAR(32) NOT NULL UNIQUE, " +
                        "curator_email VARCHAR(32) NOT NULL UNIQUE, " +
                        "curator_first_name VARCHAR(32) NOT NULL, " +
                        "curator_last_name VARCHAR(32) NOT NULL, " +
                        "curator_password VARCHAR(32) NOT NULL, " +
                        "no_of_followers INT DEFAULT 0, " +
                        "PRIMARY KEY(curator_id))";
                stmt.execute(curatorDefinition);
                System.out.println("Curator table is created successfully.\n");

                //CURATOR REVIEW
                String curatorreviewDefinition = "CREATE TABLE curatorreview " +
                        "(c_review_id INT AUTO_INCREMENT, " +
                        "c_review_text VARCHAR(32) NOT NULL, " +
                        "c_review_score VARCHAR(32) NOT NULL, " +
                        "PRIMARY KEY(c_review_id))";
                stmt.execute(curatorreviewDefinition);
                System.out.println("CuratorReview table is created successfully.\n");

                String publishDefinition = "CREATE TABLE publish " +
                        "(c_review_id INT, " +
                        "curator_id INT, " +
                        "game_id INT, " +
                        "PRIMARY KEY(c_review_id), " +
                        "UNIQUE(curator_id, game_id), " +
                        "FOREIGN KEY (c_review_id) REFERENCES curatorreview(c_review_id), " +
                        "FOREIGN KEY (curator_id) REFERENCES curator(curator_id), " +
                        "FOREIGN KEY (game_id) REFERENCES game(game_id))";
                stmt.execute(publishDefinition);
                System.out.println("Publish table is created successfully.\n");

                //FOLLOW
                String followDefinition = "CREATE TABLE follow " +
                        "(person_id INT, " +
                        "curator_id INT, " +
                        "PRIMARY KEY(person_id, curator_id), " +
                        "FOREIGN KEY (person_id) REFERENCES person(person_id), " +
                        "FOREIGN KEY (curator_id) REFERENCES curator(curator_id))";
                stmt.execute(followDefinition);
                System.out.println("Follow table is created successfully.\n");

                //SUGGEST GAME LIST
                String suggestgamelistDefinition = "CREATE TABLE suggestgamelist " +
                        "(list_id INT AUTO_INCREMENT, " +
                        "list_name VARCHAR(32) NOT NULL, " +
                        "curator_id INT, " +
                        "PRIMARY KEY(list_id), " +
                        "FOREIGN KEY (curator_id) REFERENCES curator(curator_id))";
                stmt.execute(suggestgamelistDefinition);
                System.out.println("SuggestGameList table is created successfully.\n");


                //GAME LIST CONTAIN
                String gamelistcontainDefinition = "CREATE TABLE gamelistcontain " +
                        "(list_id INT, " +
                        "game_id INT, " +
                        "PRIMARY KEY(list_id, game_id), " +
                        "FOREIGN KEY (list_id) REFERENCES suggestgamelist(list_id), " +
                        "FOREIGN KEY (game_id) REFERENCES game(game_id))";
                stmt.execute(gamelistcontainDefinition);
                System.out.println("GameListContain table is created successfully.\n");

                //WISHLIST
                String wishlistDefinition = "CREATE TABLE wishlist " +
                        "(wishlist_id INT AUTO_INCREMENT, " +
                        "list_id INT, " +
                        "person_id INT, " +
                        "PRIMARY KEY(wishlist_id), " +
                        "UNIQUE(list_id, person_id), " +
                        "FOREIGN KEY (list_id) REFERENCES suggestgamelist(list_id), " +
                        "FOREIGN KEY (person_id) REFERENCES person(person_id))";
                stmt.execute(wishlistDefinition);
                System.out.println("Wishlist table is created successfully.\n");

                //DEVELOPER
                String developerDefinition = "CREATE TABLE developer " +
                        "(developer_id INT AUTO_INCREMENT, " +
                        "developer_login_name VARCHAR(32) NOT NULL UNIQUE, " +
                        "developer_name VARCHAR(32) NOT NULL, " +
                        "developer_email VARCHAR(32) NOT NULL UNIQUE, " +
                        "developer_password VARCHAR(32) NOT NULL, " +
                        "PRIMARY KEY(developer_id))";
                stmt.execute(developerDefinition);
                System.out.println("Developer table is created successfully.\n");

                //TESTER
                String testerDefinition = "CREATE TABLE tester " +
                        "(tester_id INT AUTO_INCREMENT, " +
                        "tester_login_name VARCHAR(32) NOT NULL UNIQUE, " +
                        "tester_email VARCHAR(32) NOT NULL UNIQUE, " +
                        "tester_first_name VARCHAR(32) NOT NULL, " +
                        "tester_last_name VARCHAR(32) NOT NULL, " +
                        "tester_password VARCHAR(32) NOT NULL, " +
                        "PRIMARY KEY(tester_id))";
                stmt.execute(testerDefinition);
                System.out.println("Tester table is created successfully.\n");

                //BUG REPORT
                String bugreportDefinition = "CREATE TABLE bugreport " +
                        "(report_id INT AUTO_INCREMENT, " +
                        "report_description VARCHAR(200) NOT NULL, " +
                        "PRIMARY KEY(report_id))";
                stmt.execute(bugreportDefinition);
                System.out.println("BugReport table is created successfully.\n");

                //DEBUG
                String debugDefinition = "CREATE TABLE debug " +
                        "(report_id INT, " +
                        "tester_id INT, " +
                        "game_id INT, " +
                        "developer_id INT, " +
                        "PRIMARY KEY(report_id), " +
                        "FOREIGN KEY (tester_id) REFERENCES tester(tester_id), " +
                        "FOREIGN KEY (report_id) REFERENCES bugreport(report_id), " +
                        "FOREIGN KEY (game_id) REFERENCES game(game_id), " +
                        "FOREIGN KEY (developer_id) REFERENCES developer(developer_id))";
                stmt.execute(debugDefinition);
                System.out.println("Debug table is created successfully.\n");

                //UPDATE
                String updateDefinition = "CREATE TABLE updategame " +
                        "(game_id INT, " +
                        "developer_id INT, " +
                        "update_desc VARCHAR(100) NOT NULL, " +
                        "new_version_no FLOAT NOT NULL, " +
                        "PRIMARY KEY(game_id), " +
                        "FOREIGN KEY (developer_id) REFERENCES developer(developer_id), " +
                        "FOREIGN KEY (game_id) REFERENCES game(game_id))";
                stmt.execute(updateDefinition);
                System.out.println("Update table is created successfully.\n");

                //ASK
                String askDefinition = "CREATE TABLE ask " +
                        "(publisher_id INT, " +
                        "developer_id INT, " +
                        "req_id INT, " +
                        "ask_game_name VARCHAR(50) NOT NULL, " +
                        "ask_game_genre VARCHAR(30) NOT NULL, " +
                        "ask_game_desc VARCHAR(50) NOT NULL, " +
                        "approval ENUM('Available', 'Waiting for Approval', 'Declined', 'Accepted') DEFAULT 'Waiting for Approval', " +
                        "PRIMARY KEY(publisher_id, developer_id, ask_game_name), " +
                        "FOREIGN KEY (publisher_id) REFERENCES publisher(publisher_id), " +
                        "FOREIGN KEY (req_id) REFERENCES systemrequirements(req_id), " +
                        "FOREIGN KEY (developer_id) REFERENCES developer(developer_id))";
                stmt.execute(askDefinition);
                System.out.println("Ask table is created successfully.\n");

                //PUBLISHGAME
                String publishgameDefinition = "CREATE TABLE publishgame " +
                        "(publisher_id INT, " +
                        "game_id INT, " +
                        "discount FLOAT NOT NULL, " +
                        "PRIMARY KEY(game_id), " +
                        "FOREIGN KEY (publisher_id) REFERENCES publisher(publisher_id), " +
                        "FOREIGN KEY (game_id) REFERENCES game(game_id))";
                stmt.execute(publishgameDefinition);
                System.out.println("PublishGame table is created successfully.\n");

                //INSERTION

                //PERSON INSERTION
                String personInsert = "INSERT INTO person(nick_name, email, password, person_name, person_surname) VALUES" +
                        "( 'u1', 'u1', 'u1','u1', 'u1')";
                System.out.println("Starting to insert values to person table...");
                stmt.execute(personInsert);
                System.out.println("Values are inserted to person table.\n");

                //CURATOR INSERTION
                String curatorInsert = "INSERT INTO curator VALUES" +
                        "(1, 'c1', 'c1', 'c1', 'c1', 'c1', 0)";
                System.out.println("Starting to insert values to curator table...");
                stmt.execute(curatorInsert);
                System.out.println("Values are inserted to curator table.\n");

                //PUBLISHER INSERTION
                String publisherInsert = "INSERT INTO publisher VALUES" +
                        "(1, 'p1', 'p1', 'p1', 'p1')";
                System.out.println("Starting to insert values to publisher table...");
                stmt.execute(publisherInsert);
                System.out.println("Values are inserted to publisher table.\n");

                //DEVELOPER INSERTION
                String developerInsert = "INSERT INTO developer VALUES" +
                        "(1, 'd1', 'd1', 'd1', 'd1')";
                System.out.println("Starting to insert values to developer table...");
                stmt.execute(developerInsert);
                System.out.println("Values are inserted to developer table.\n");

                //TESTER INSERTION
                String testerInsert = "INSERT INTO tester VALUES" +
                        "(1, 't1', 't1', 't1', 't1', 't1')";
                System.out.println("Starting to insert values to tester table...");
                stmt.execute(testerInsert);
                System.out.println("Values are inserted to tester table.\n");


                //GAME NAME TO GAME ID STORED PROCEDURE
                String GameNameToGameId = "CREATE OR REPLACE PROCEDURE GameNameToGameId(IN gameName VARCHAR(32)) BEGIN SELECT game_id FROM game WHERE game_name = gameName; END;";
                System.out.println("GameNameToGameId Stored Procedure is inserting");
                stmt.execute(GameNameToGameId);
                System.out.println("GameNameToGameId Stored Procedure is inserted\n");

                //GAME ID TO GAME ID NAME PROCEDURE
                String GameIdToGameName = "CREATE OR REPLACE PROCEDURE GameIdToGameName(IN gameId INT) BEGIN SELECT game_name FROM game WHERE game_id = gameId; END;";
                System.out.println("GameIdToGameName Stored Procedure is inserting");
                stmt.execute(GameIdToGameName);
                System.out.println("GameIdToGameName Stored Procedure is inserted\n");

                //before_update_credits TRIGGER
                String creditsTrigger = "CREATE OR REPLACE TRIGGER before_update_credits BEFORE UPDATE ON person FOR EACH ROW INSERT trigger_credits VALUES(\"Credits Action\");";
                System.out.println("creditsTrigger is inserting");
                stmt.execute(creditsTrigger);
                System.out.println("creditsTrigger inserted\n");

                //TRIGGER CREDITS TABLE
                String triggerCreditsDefinition = "CREATE TABLE trigger_credits " +
                        "(message VARCHAR (32))";
                stmt.execute(triggerCreditsDefinition);
                System.out.println("trigger_credits is created successfully.\n");

            }


        }


        catch (SQLException | ClassNotFoundException | FileNotFoundException e) {
            System.err.println("Error Statement or Connection Failed!!!!");
            e.printStackTrace();
        } catch (IOException e) {
            e.printStackTrace();
        }
    }
}