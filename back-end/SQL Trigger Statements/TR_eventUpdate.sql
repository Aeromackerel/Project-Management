/****** Object:  Trigger [dbo].[TR_eventUpdate]    Script Date: 4/14/2019 10:28:14 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
/****** Object:  Trigger [dbo].[TR_eventUpdate]    Script Date: 4/14/2019 1:49:39 AM ******/
ALTER TRIGGER [dbo].[TR_eventUpdate]
ON [dbo].[Events]
FOR UPDATE
AS
IF(UPDATE(startDateTime) OR UPDATE(endDateTime))
BEGIN
	PRINT 'Events have been updated - please take a look at the events listing.'
END
