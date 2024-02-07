note-app tables
----------------

users tables 
 ----------
 id (integer)
 name (varchar)
 email (varchar)
 password (varchar)
 created_at (datetime)

 
 notes table
  -------- 
  id (integer)
  users_id (integer)
  title (varchar)
  description (text)
  created_at (datetime)
  updated_at (datetime)
