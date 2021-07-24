	  google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

		var count=incomeNamesArray.length;
		var dataMatrix= [];
		dataMatrix[0] = ['Task', 'Hours per Day'];
		 for( var i=0 ; i < count ; i++)
		 {
			 dataMatrix[i+1]=[ incomeNamesArray[ i ], incomeAmountsArray[ i ] ];
		 } 
		
        var data = google.visualization.arrayToDataTable(dataMatrix);

        var options = {
          title: '',
		  backgroundColor: '#30B2C7',
		  chartArea:{left:20,top:40,width:'90%',height:'75%'},
		  legend: {textStyle: {color: 'white', fontSize: 12}}
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechartIncomes'));

        chart.draw(data, options);
      }