#Import Moodle Datas

Before importing moodle datas, please ensure your give a correct Gplace Api key and Geoname api username. [See Installation](index.mkd)

Simply run the following Symfony command:

    php app/console pfe:collector:mooc course_id --sql-dump path_to_sql_dump.sql --env=prod
    
    course_id: The MOOC courseId to import
    --sql-dump: Path to sql-dump file
    --env: The environment name
    
Note: 

- For the Telecom SudParis course _Les bases de données relationnelles_, id is 4
- I strongly recommand to use `prod` environment, even in development due to memory consumption of this script.