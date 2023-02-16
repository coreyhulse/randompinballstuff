This page is to help those using Windows get started on a local development branch.

Is your virtual environment good and ready to go?

Yes:
* Open CMD.
* Navigate to the folder on your machine that has the dbt repo in it.
* Start your virtual environment by entering `dbtvenv\Scripts\activate.bat`.
* Make sure you're on the `main` branch of the repo.
* Run `dbt run-operation refresh_dev`.
* Run `dbt clean`.
* Start a new branch.
* Develop!
* When you're done with development simply `deactivate`.

No:
* Open CMD.
* Navigate to the folder on your machine that has the dbt repo in it.
* Is your Python Virtual Environment messed up?  If so, run `del dbtvenv` (Windows) to delete the virtual environment.
* Do you need a new virtual environment?  Create a new virtual environment by entering `python -m venv dbtvenv`.
* Start your virtual environment by entering `dbtvenv\Scripts\activate.bat`.
* Is this a new virtual environment?  `pip install -r prerequisites.txt`.
* Is this a new virtual environment?  `pip install -r requirements.txt`.
* Make sure you're on the `main` branch of the repo.
* Run `dbt run-operation refresh_dev`.
* Run `dbt clean`.
* Start a new branch.
* Develop!
* When you're done with development simply `deactivate`.
