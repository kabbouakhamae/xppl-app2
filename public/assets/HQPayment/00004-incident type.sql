	
	DECLARE @datefr DATETIME = '2023/01/01'
	DECLARE @dateto DATETIME = '2023/01/17'

	DECLARE @data TABLE(
		item VARCHAR(50)
		,com VARCHAR(100)
		,inc_type VARCHAR(100)
		,cnt INT
	);


	DECLARE @xppl VARCHAR(100)
	DECLARE @cont VARCHAR(100)

	SET @xppl = (SELECT COUNT(*) FROM dbo.saf_incident_records WHERE inc_date BETWEEN @datefr AND @dateto AND company = 'xppl')
	SET @cont = (SELECT COUNT(*) FROM dbo.saf_incident_records WHERE inc_date BETWEEN @datefr AND @dateto AND company != 'xppl')

	--SELECT @xppl

	IF @xppl = 0 
		BEGIN
			INSERT INTO @data (item, com, cnt)
			SELECT 'item04', 'XPPL', 0 UNION
			SELECT 'item05', 'XPPL', 0 UNION
			SELECT 'item05', 'Total', 0
		END
	ELSE
		BEGIN
		
			INSERT INTO @data (item, com, inc_type, cnt)
			SELECT
				'item04'
				,'XPPL'
				,inc_type
				,COUNT(inc_id) 
			FROM
				dbo.saf_incident_records
			WHERE 
				inc_date BETWEEN @datefr AND @dateto
				AND company = 'xppl'
			GROUP BY 
				inc_type
		
			UNION

			SELECT
				'item05'
				,dept
				,inc_type
				,COUNT(inc_id) 
			FROM
				dbo.saf_incident_records
			WHERE 
				inc_date BETWEEN @datefr AND @dateto
				AND company = 'xppl'
			GROUP BY 
				dept
				,inc_type

			UNION

			SELECT
				'item05'
				,'Total'
				,inc_type
				,COUNT(inc_id) 
			FROM
				dbo.saf_incident_records
			WHERE 
				inc_date BETWEEN @datefr AND @dateto
				AND company = 'xppl'
			GROUP BY 
				inc_type
		END


	IF @cont = 0 
		BEGIN
			INSERT INTO @data (item, com, cnt)
			SELECT 'item04', 'Contractor', 0 UNION
			SELECT 'item06', 'Contractor', 0 UNION
			SELECT 'item06', 'Total', 0
		END
	ELSE
		BEGIN
		
			INSERT INTO @data (item, com, inc_type, cnt)
			SELECT
				'item04'
				,'Contractor'
				,inc_type
				,COUNT(inc_id) 
			FROM
				dbo.saf_incident_records
			WHERE 
				inc_date BETWEEN @datefr AND @dateto
				AND company != 'xppl'
			GROUP BY 
				inc_type
		
			UNION

			SELECT
				'item06'
				,dept
				,inc_type
				,COUNT(inc_id) 
			FROM
				dbo.saf_incident_records
			WHERE 
				inc_date BETWEEN @datefr AND @dateto
				AND company != 'xppl'
			GROUP BY 
				dept
				,inc_type

			UNION

			SELECT
				'item06'
				,'Total'
				,inc_type
				,COUNT(inc_id) 
			FROM
				dbo.saf_incident_records
			WHERE 
				inc_date BETWEEN @datefr AND @dateto
				AND company != 'xppl'
			GROUP BY 
				inc_type
		END

	IF @xppl = 0 AND @cont = 0
		BEGIN
			INSERT INTO @data (item, com, cnt)
			SELECT 'item04', 'Total', 0
		END
	ELSE
		BEGIN
			INSERT INTO @data (item, com, inc_type, cnt)
			SELECT
				'item04'
				,'Total'
				,inc_type
				,COUNT(inc_id) 
			FROM
				dbo.saf_incident_records
			WHERE 
				inc_date BETWEEN @datefr AND @dateto
			GROUP BY 
				inc_type
		END



	SELECT
		*
	FROM
		(
			SELECT
				*
			FROM
				@data
		) AS p
	PIVOT
		(SUM(cnt) FOR inc_type IN([Incident],[Injury],[Injury (Non Work Related)],[Injury/ Equipment Damage],[Near Miss],[Property Damage],[Unsafe Act],[Unsafe Condition])) AS f	
	ORDER BY 
		f.item
		,CASE WHEN f.com = 'xppl' THEN 1 WHEN f.com = 'total' THEN 99 ELSE 2 END
		,f.com
      














