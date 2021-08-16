//SET TIME BORDERS

		var maxDay;
		var year;
		
		function setTimeBorders()
		{
			var selectorValue = document.getElementById("timeBordersSelector").value;
			var now = new Date();
			var downBorder='2000-01-01';
			var topBorder='2000-01-01';
			var month = (now.getMonth() + 1);
			year= now.getFullYear();
			
			switch(selectorValue)
			{
				case "Current month":
					setMaxDay( month );
					if (month < 10) month = '0' + month;
					downBorder= year+'-'+month+'-01';
					topBorder= year+'-'+month+'-'+maxDay;
					break;
				case "Previous month":
					if( month != 1) month--;
					else
					{
						month=12;
						year--;
					}
					setMaxDay( month );
					if (month < 10) month = '0' + month;
					downBorder= year+'-'+month+'-01';
					topBorder= year+'-'+month+'-'+maxDay;
					break;
				case "Current year":
					downBorder=year + '-01-01';
					topBorder=year + '-12-31';
					break;
				case "Custom":
					document.getElementById("topDateBorder").disabled = false;
					document.getElementById("downDateBorder").disabled = false;
					break;
			}
			document.getElementById("downDateBorder").value = downBorder;
			document.getElementById("topDateBorder").value = topBorder;
		}
		
		function setMaxDay( month )
		{
			if( month == 1 || month == 3 || month == 5 || month == 7 || month == 8 || month == 10 || month == 12)
			{
				maxDay = 31;
			}
			else if( month  == 2 )
			{
				if(year%4 ==0) maxDay = 29;
				else maxDay = 28;
			}
			else maxDay = 30;
		}


		var incomeNamesArray  = [];
		var expenseNamesArray  = [];
		var incomeAmountsArray=[];
		var expenseAmountsArray=[];

		let i=0;
		{% for j in range(0, balance.incomeCategoryNames|length) %}
			incomeNamesArray[ i ]= "{{ balance.incomeCategoryNames[  j ]}}";
			incomeAmountsArray[ i ]= parseFloat( "{{ balance.incomesSummaryAmount[  j ] }}");
			i++;
		{% endfor %}
		i=0;
		
		{% for j in range(0, balance.expenseCategoryNames|length) %}
			expenseNamesArray[ i ]= "{{ balance.expenseCategoryNames[  j ]}}";
			expenseAmountsArray[ i ]= parseFloat( "{{ balance.expensesSummaryAmount[  j ] }}");
			i++;
		{% endfor %}

//AJAX 

