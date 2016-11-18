<?php

**
 * PHP version 5
 *
 * @category DocuSign 
 * @description Implementation to send documents to internal Clients, Advisors
 * @date     May 2016
 * @author   http://github.com/gibaless
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache Licene v2
 * @link     https://github.com/gibaless/API
 */


include '../core/init.php';
//protect_page();
?>
   <?php include_once('head.php'); ?>

    <body class="infobar-offcanvas">
        <?php include_once('header_menu.php'); ?>
            <div id="wrapper">
                <div id="layout-static">
                    <?php include_once('sidebar_menu.php'); ?>
                        <div class="static-content-wrapper">
                            <div class="static-content">
                                <div class="page-content">
                                    <ol class="breadcrumb">
                                        <li class=""><a href="index.php">Home</a></li>
                                        <li class="active"><a href="docuSign.php">docuSign (test page)</a></li>
                                    </ol>
                                    <div class="page-heading">
                                        <h1>docuSign page</h1>
                                        <div class="options"></div>
                                    </div>





 <?php
    require_once('../core/functions/autoload-docuSign.php');
    require_once('../core/functions/docuSign-functions.php');
    $conn55 = db_connect();
    mysqli_autocommit($conn55, false);
    $flag_commit = true;
    // ----------------------------------------
    // 1. Create a new process
    // -----------------------------------------------------------------
    // This triggers to send process_id 0 - Full Access SubAdvisory Agreements
    // process_header = 0
    // doc_process_line_settings = 1
    // doc_process_line

      // ----------------------------------------
      // 2. Create 3 docs into the table
      // -----------------------------------------------------------------
      // Envelope Status ID
      // 1	Created
      // 2	Deleted
      // 3	Sent
      // 4	Delivered
      // 5	Signed
      // 6	Completed
      // 7	Declined
      // 8	Voided
      // 9	TimesOut
      // 10	Template
      // 11	Correct
      // ----------------------------------------------------------

      $sql_array_docs = "CALL docusign_new_doc('1074','' , '','". $user_data['email1'] . "','" . $user_data['ria_user_id'] . "','ga@servicerw.com', '', 0, DATE(CURDATE()), '', '', '/documents/list_docs/')";
  

      $result_new_doc1 = mysqli_query($conn55, $sql_array_docs );
        if( $result_new_doc1 === false) {
          //  echo "Error occurred: " . mysqli_errno().': '. mysqli_error();
          }
        else {
          //Get the process ID in order to use it for next INSERTs
            $last_doc_1_id = mysqli_query($conn55, "SELECT LAST_INSERT_ID()");
            $row1=mysqli_fetch_row($last_doc_1_id);
            $docId1 = $row1[0];
          //This does not work $last_process_id = mysqli_insert_id($con);
            //echo "<br/>New doc was created. Doc ID: " . $docId1;
          }

    // 2. Trigger the email to the RIA user logged
    $envelope = CreateAndSendEnvelope($user_data['first_name'], $user_data['email1'], $docId1);

    if ($flag_commit) {
        mysqli_commit($conn55);
        echo "<br/>All queries were executed successfully";
        } else {
        mysqli_rollback($conn55);
        echo "<br/>All queries were rolled back";
      }
    //release results
      //Get the 3 docs and store them in ria_docs table and ria_doc_process table
?>
</div>
</div>
</div>
</div>
<!-- #page-content -->
</div>
 <?php include_once('footer.php'); ?>
</div>
</div>
</div>
<script type="text/javascript" src="assets/js/jquery-1.10.2.min.js"></script>
<!-- Load jQuery -->
<script type="text/javascript" src="assets/js/jqueryui-1.9.2.min.js"></script>
<!-- Load jQueryUI -->
<script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
<!-- Load Bootstrap -->

</body>
</html>
