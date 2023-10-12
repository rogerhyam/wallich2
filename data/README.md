

If moving db MySQL 5.7 to 8.0 you need to change the encoding

sed -i 's/utf8mb4_0900_ai_ci/utf8mb4_general_ci/g' wallich_db.sql