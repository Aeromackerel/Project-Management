/****** Object:  Trigger [dbo].[trigger_checkTimesheet]    Script Date: 4/22/2019 5:47:58 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
ALTER TRIGGER [dbo].[trigger_checkTimesheet]
ON [dbo].[Timesheet]
AFTER UPDATE, INSERT
AS
BEGIN
	SET NOCOUNT ON;

	DECLARE @tempId int
	DECLARE @tempHours float
	DECLARE @tempDate DATE
	DECLARE @previousHours float

	SET @tempId = (SELECT employeeId FROM inserted)
	SET @tempHours = (SELECT hours FROM inserted)
	SET @tempDate = (SELECT date FROM inserted)

	SET @previousHours =(SELECT sum(hours) FROM Timesheet WHERE employeeId = @tempId AND date = (select convert(varchar(10),getDate(),120)))

	IF (@tempHours + (@previousHours - @tempHours) > 12)
	BEGIN
		UPDATE Timesheet
		SET hours = d.Hours
		FROM Timesheet T INNER JOIN deleted d
		ON T.timesheetId = d.timesheetId
	END
END

