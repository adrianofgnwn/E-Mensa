SELECT ersteller.name, COUNT(Wunschgericht.id) AS Anzahl_Wünsche
FROM Ersteller
         LEFT JOIN Wunschgericht ON Ersteller.id = Wunschgericht.creator_id
GROUP BY Ersteller.name;
