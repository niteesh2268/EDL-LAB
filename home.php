<?php
//AJAX Search for component/equipment
session_start();

if($_SESSION['login']!="success") header("Location: index.php");
include_once("connections/connect.php");

?>

<html>
    <head>
        <title>Home - EDL Lab</title>
       <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>

        <link rel="stylesheet" href="style/style.css" />
        <link rel="stylesheet" href="style/style_home.css" />

    </head>
    <body>
      <header>
       <nav class="navbar navbar-default navbar-fixed-top">
               <div class="container">
                       <div class="navbar-header">
                               <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#mainbar">
                                       <span class="icon-bar"></span>
                                       <span class="icon-bar"></span>
                                       <span class="icon-bar"></span>
                               </button>
                               <a class="navbar-brand logo" href="#">Sambhal</a>
                       </div>

                       <div class="collapse navbar-collapse" id="mainbar">
                               <ul class="nav navbar-nav navbar-right">
                                 <?php if($_SESSION['level']=="staff" || $_SESSION['level']=="faculty"){?>
                                        <!-- <li><a href="home.php">Requests</a></li> -->
                                <?php } ?>
                                       <li><a href="home.php">Home</a></li>
                                 <?php if($_SESSION['level']=="staff"){?>
                                        <li><a href="actions/issue.php">Issue</a></li>
                                        <li><a href="team.php">Team</a></li>
                                        <li><a href="actions/orders.php">Orders</a></li>
                                <?php } ?>
                                <?php if($_SESSION['level']=="student"){?>
                                       <li><a href="history.php">History</a></li>
                               <?php } ?>
                                <?php if($_SESSION['level']=="faculty"){?>
                                       <li><a href="actions/orders.php">Requests</a></li>
                               <?php } ?>
                                        <!-- <li><a href="timetable.php">Time Table</a></li> -->
                                       <li><a href="logout.php">Log out</a></li>
                               </ul>
                       </div>
               </div>
       </nav>
       </header>
        <div class="container">
			<br />
			<div>
        <div class="greetings">
          Hello <?php echo $_SESSION['name']; ?>, have a great day ahead!
        </div><br />
					<input type="text" name="search_text" id="search_text" placeholder="Search" class="form-control" />

          <?php
          $sql = "select * from material_type" ;
         $result = pg_query($db, $sql);

         while($row  = pg_fetch_array(  $result)){
           echo "<input class=\"search-option\" type=\"checkbox\" name=\"search-option\" value=\"".strtolower($row["name"])."\" checked> ".$row["name"]."<br />";
         }
         ?>
          <!-- <input class="search-option" type="checkbox" name="search-option" value="component" checked> Components<br />
          <input class="search-option" type="checkbox" name="search-option" value="equipment" checked> Equipments<br /> -->

					<br>
				<span id="result"></span>
				<div id="live_data" style="max-width:100%;"></div>
			</div>
		</div>
    </body>
</html>







<script>


function edit_data(value, id, change){
  console.log(id+ " " +change+" val="+value);
if(value<0){
  alert("Invalid Updation");
  var search = $("#search_text").val();
  if(search != '')
  {
    load_data(search);

  }
  else
  {
    load_data();
    // $('#result').html("<div class='alert alert-success'>"+data+"</div>");
  }
  return false;
}

  $.ajax({
      url:"./actions/edit.php",
      method:"POST",
      data:{id:id, text:value, column_name:change},
      dataType:"text",
      success:function(data){
          // alert(data);
          if(data != 'Data Updated')
          {
            alert("Error in data updation");
            var search = $("#search_text").val();
        		if(search != '')
        		{
        			load_data(search);

        		}
        		else
        		{
        			load_data();
              // $('#result').html("<div class='alert alert-success'>"+data+"</div>");
        		}
            return;
          }

          var search = $("#search_text").val();
      		if(search != '')
      		{
      			load_data(search);

      		}
      		else
      		{
      			load_data();
            // $('#result').html("<div class='alert alert-success'>"+data+"</div>");
      		}
          $('#result').html("<div class='alert alert-success'>"+data+"</div>");

      }
  });

}

	load_data();

	function load_data(search)
	{

    var selectedMaterials=[];

    var checkboxes = document.querySelectorAll('input[type=checkbox]:checked');

    for (var i = 0; i < checkboxes.length; i++) {
      if(checkboxes[i].name=="search-option")
        selectedMaterials.push(checkboxes[i].value);
    }

    if(selectedMaterials.length == 0){
      // window.alert("Please select an option to search");
      // return;
        selectedMaterials_json = null;
    }
    // console.log(checkboxes[0].value);
    console.log(selectedMaterials);

    selectedMaterials_json = JSON.stringify(selectedMaterials);

		$.ajax({
			url:"./actions/select.php",
			method:"post",
			data:{query:search, selectedMaterials:selectedMaterials_json },
			success:function(data)
			{
        setTimeout(function(){ $('#result').html(""); }, 4000);
				$('#live_data').html(data);
			}
		});
	}

  $('.search-option').change(function(){
    var search = $("#search_text").val();
    if(search != '')
    {
      load_data(search);
    }
    else
    {
      load_data();
    }
  });

	 $('#search_text').keyup(function(){
		var search = $(this).val();
		if(search != '')
	 	{
	 		load_data(search);
	 	}
	 	else
	 	{
	 		load_data();
	 	}
	 });



  $(document).on('click', '#btn_add', function()
  {
        var name = $('#new-name').val();
		    var type = $("#a").val();
        var cost = $('#new-cost').val();
		    var quantity = $('#new-quantity').val();
		    var comment = $('#new-comment').val();
        if(name == '')
        {
            alert("Enter name");
            return false;
        }
		    if(type == '')
        {
            alert("Enter type");
            return false;
        }
        if(cost == '')
        {
            alert("Enter cost");
            return false;
        }
		    if(quantity == '')
        {
            alert("Enter quantity");
            return false;
        }
      if(cost <0)
      {
          alert("Enter valid cost");
          return false;
      }
      if(quantity <0)
      {
          alert("Enter valid Quantity");
          return false;
      }
		    // if(comment == '')
        // {
        //     alert("Enter comment");
        //     return false;
        // }
        $.ajax({
            url:"./actions/insert.php",
            method:"POST",
            data:{name:name,type:type,cost:cost,quantity:quantity,comment:comment},
            dataType:"text",
            success:function(data)
            {
                // alert(data);
                if(data != 'Data Inserted')
                {
                  alert("Error in data values");
                  var search = $("#search_text").val();
              		if(search != '')
              		{
              			load_data(search);

              		}
              		else
              		{
              			load_data();
                    // $('#result').html("<div class='alert alert-success'>"+data+"</div>");
              		}
                  return;
                }
                var search = $('#search_text').val();
                if(search != '')
                {
                   load_data(search);
                }
                else
                {
                  load_data();
                }
            }
        })
    });

    $(document).on('click', '#btn_request', function()
    {
          var name = $('#new-name').val();
  		     var type = $("#a").val();
          var cost = $('#new-cost').val();
  		    var quantity = $('#new-quantity').val();
  		    var cause = $('#new-cause').val();
          var facultyRef = $('#new-faculty-ref').val();
          if(name == '')
          {
              alert("Enter name");
              return false;
          }
  		    if(type == '')
          {
              alert("Enter type");
              return false;
          }
          if(cost == '')
          {
              alert("Enter cost");
              return false;
          }
  		    if(quantity == '')
          {
              alert("Enter quantity");
              return false;
          }
  		    // if(comment == '')
          // {
          //     alert("Enter comment");
          //     return false;
          // }
          $.ajax({
              url:"./actions/request_material.php",
              method:"POST",
              data:{name:name,type:type,cost:cost,quantity:quantity,cause:cause, facultyRef: facultyRef},
              dataType:"text",
              success:function(data)
              {
                  alert(data);
                  var search = $('#search_text').val();
                  if(search != '')
                  {
                     load_data(search);
                  }
                  else
                  {
                    load_data();
                  }
              }
          })
      });

	// function edit_data(id, text, column_name)
  //   {
  //       $.ajax({
  //           url:"./actions/edit.php",
  //           method:"POST",
  //           data:{id:id, text:text, column_name:column_name},
  //           dataType:"text",
  //           success:function(data){
  //               //alert(data);
	// 			$('#result').html("<div class='alert alert-success'>"+data+"</div>");
  //           }
  //       });
  //   }
  //   $(document).on('change', '.name', function(){
  //       var id = $(this).data("id1");
  //       var name = $(this).text();
  //       edit_data(id, name, "name");
  //   });
	// $(document).on('change', '.type', function(){
  //       var id = $(this).data("id2");
  //       var type = $(this).text();
  //       edit_data(id, type, "type");
  //   });
  //   $(document).on('change', '.cost', function(){
  //       var id = $(this).data("id3");
  //       var cost = $(this).text();
  //       edit_data(id,cost, "cost");
  //   });
	// $(document).on('change', '.quantity', function(){
  //       var id = $(this).data("id4");
  //       var quantity = $(this).text();
  //       edit_data(id,quantity, "quantity");
  //   });
	// $(document).on('change', '.comment', function(){
  //       var id = $(this).data("id5");
  //       var comment = $(this).text();
  //       edit_data(id,comment, "comment");
  //   });




    $(document).on('click', '.btn_delete', function(){
        var id=$(this).data("id6");
        if(confirm("Are you sure you want to delete this?"))
        {
            $.ajax({
                url:"./actions/delete.php",
                method:"POST",
                data:{id:id},
                dataType:"text",
                success:function(data)
                {
                    // alert(data);
                    if(data == 'failure')
                    {
                      alert('error in deletion');
                    }
                    else
                    {
                      var search = $('#search_text').val();
  					          if(search != '')
  					          {
  						           load_data(search);
  					          }
                      else
            					{
            						load_data();
            					}
                    }

                }
            });
        }
    });


    // $(document).on('click', '.btn_issue', function(){
    //     var id=$(this).data("id7");
    //     if(confirm("Are you sure you want to issue this?"))
    //     {
    //         $.ajax({
    //             url:"./actions/issue.php",
    //             method:"POST",
    //             data:{id:id},
    //             dataType:"text",
    //             success:function(data)
    //             {
    //                 alert(data);
    //                 var search = $('#search_text').val();
		// 			          if(search != '')
		// 			          {
		// 				           load_data(search);
		// 			          }
    //                 else
    //       					{
    //       						load_data();
    //       					}
    //             }
    //         });
    //     }
    // });

</script>
