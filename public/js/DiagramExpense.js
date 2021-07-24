	  google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawExpenseChart);

      function drawExpenseChart() {

		var count=expenseNamesArray.length;
		var dataMatrixx= [];
		dataMatrixx[0] = ['Task', 'Hours per Day'];
		 for( var i=0 ; i < count ; i++)
		 {
			 dataMatrixx[i+1]=[ expenseNamesArray[ i ], expenseAmountsArray[ i ] ];
		 } 
		
        var data = google.visualization.arrayToDataTable(dataMatrixx);

        var options = {
          title: '',
		  backgroundColor: '#30B2C7',
		  chartArea:{left:20,top:40,width:'90%',height:'75%'},
		  legend: {textStyle: {color: 'white', fontSize: 12}}
        };

        var chartt = new google.visualization.PieChart(document.getElementById('piechartExpenses'));

        chartt.draw(data, options);
      }