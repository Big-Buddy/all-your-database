Select Users.UserID, Users.Email, ('Yes') as isAdmin, ('Yes') as canDoAdminStuff from Users
	where UserType = 'Admin'