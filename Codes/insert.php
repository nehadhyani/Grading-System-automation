<?php
    session_start();
    if(isset($_SESSION['id']))
    {
        require_once __DIR__ . '\connection\connect.php';

        if (isset($_GET['course']))
        {
            $cd = $_GET['course'];
        }
        else  
        {
            header('Location: edit.php?error=ERROR OCCURRED');
            exit();
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            //Updating maximum marks
            $mta=($_POST['mct1']+$_POST['mct2']+$_POST['mct3']+$_POST['mct4']+$_POST['mmt1']+$_POST['mmt2']);
            $met=100-$mta;
            if($mta<=100 && $met==$_POST['met'])
            {
                $sql="UPDATE controlsheet SET class_test_1=".$_POST['mct1'].", class_test_2=".$_POST['mct2'].", class_test_3=".$_POST['mct3'].", class_test_4=".$_POST['mct4'].", 
                mid_term_1=".$_POST['mmt1'].", mid_term_2=".$_POST['mmt2'].", total_assesment=".$mta.", end_term=".$met." WHERE course_code='$cd' AND name='Maximim Marks'";
                if($conn->query($sql) !== TRUE)
                {
                    header('Location: edit.php?course='.$cd.'&error=ERROR UPDATING RECORD');
                    exit();
                }
            }
            else
            {
                //Total assessment marks >100 OR endterm marks + total assessment marks != 100!!
                header('Location: edit.php?course='.$cd.'&error=ERROR OCCURRED');
                exit();
            }

            //Updating marks of each student
            $c=count($_POST['roll']);
            $i=1;
            for($i=0;$i<$c;$i++)
            {
                $r=$_POST['roll'][$i];
                $ta=($_POST['ct1'][$i]+$_POST['ct2'][$i]+$_POST['ct3'][$i]+$_POST['ct4'][$i]+$_POST['mt1'][$i]+$_POST['mt2'][$i]);
                $tm=$ta+$_POST['endterm'][$i];
                if($_POST['ct1'][$i]<=$_POST['mct1'] && $_POST['ct2'][$i]<=$_POST['mct2'] && $_POST['ct3'][$i]<=$_POST['mct3'] && $_POST['ct4'][$i]<=$_POST['mct4'] && $_POST['mt1'][$i]<=$_POST['mmt1']  && $_POST['mt2'][$i]<=$_POST['mmt2'] && $_POST['endterm'][$i]<=$met)
                {
                    $sql="UPDATE controlsheet SET class_test_1=".$_POST['ct1'][$i].", class_test_2=".$_POST['ct2'][$i].", class_test_3=".$_POST['ct3'][$i].", class_test_4=".$_POST['ct4'][$i].", mid_term_1=".$_POST['mt1'][$i].", 
                    mid_term_2=".$_POST['mt2'][$i].", total_assesment=".$ta.", end_term=".$_POST['endterm'][$i].", total_marks=".$tm." WHERE course_code='$cd' AND roll_no='$r'";

                    if ($conn->query($sql) !== TRUE)
                    {
                        header('Location: edit.php?course='.$cd.'&error=ERROR UPDATING RECORD');
                        exit();
                    }
                }
                else
                {
                    //Marks exceeding maximum marks!!
                    header('Location: edit.php?course='.$cd.'&error=ERROR OCCURRED');
                    exit();
                }
            }
            require_once __DIR__ . '\connection\disconnect.php';

            header('Location: coursetable.php?course='.$cd);
            exit();
        }
        else
        {
            header('Location: coursetable.php?error=ERROR OCCURRED');
            exit();
        }
    }
    else
    {
        header('Location: index.php?error=INVALID REQUEST');
        exit();
    }
?>