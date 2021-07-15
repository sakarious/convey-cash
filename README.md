
# Convey Cash API

> ### Convey Cash API documentation.

---


# API Specification

## Features

-   Register.
-   Login
-   Verify/resolve an account number.
-   Transfer funds to a valid account number
-   Search and List all previous transactions

## Allowed HTTP Requests

-   GET
-   POST

## Base URL

`http://127.0.0.1:8000/api/v1`

## Endpoints

-   `POST: /register` - Register and get an access token for authorization
-   `POST: /login` - Login and get an access token for authorization.
-   `POST: /verify` - Verify/resolve an account number.
-   `POST: /transfer` - Transfer funds to a valid account number.
-   `GET: /history?accountNumber=2111333996` - Search for user transaction history by account number.
-   `GET: /history` - List user transaction history.

## Resources

### Register

---

Returns json data of user details and access token.


-   **URL and Method**
    `POST http://127.0.0.1:8000/api/v1/register`

-   **Request**

```
POST http://127.0.0.1:8000/api/v1/register
Content-Type: application/json

{
  "name": "John Doe",
  "email": "johndoe@gmail.com",
  "password": "secret",
  "password_confirmation": "secret"
}
```

With the following fields:

| Parameter       | Required? | Description              |
| --------------- | --------- | ------------------------ |
| name            | required  | User name.            |
| email           | required  | User email             |
| password        | required  | User Password.            |
| password_confirmation        | required  | Confirm User Password.         |


-   **Success Response**

```
Status 201 Created
Content-Type: application/json; charset=utf-8

{
  "message": "Account Created Successfully",
  "data": {
    "user": {
      "name": "John Doe",
      "email": "johndoe@gmail.com",
      "id": 3
    },
    "accessToken": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIzIiwianRpIjoiMjg4YWFmN2Y4YzVlODBhZWQwNjcwNjA0NjExODYyN2YyYjE0MmVmOWRiMjNlNWU2YTE2NzdjNDI0Mzg1NzQ0MmJkNzQ2NGIzZWJhMTg0ZDIiLCJpYXQiOjE2MjYyODI3MDAsIm5iZiI6MTYyNjI4MjcwMCwiZXhwIjoxNjU3ODE4NzAwLCJzdWIiOiIzIiwic2NvcGVzIjpbXX0.SFxXYzPMQBuGyyjHAss49KlU4tawxk45kXywdT_uHbXNfyGKHXJ9LHNRDRn2nk5AtUDRtYmxrIyORmbfdjQHQRahHDc_KT-YpSXiLK39aDg5BBoAJ3mo0uhlIfW1ZjpDp0w0odv4c0WAcBClooqJmkPa5-lYMrTFULqWiU65x15guAS6ux7YXoOTsqnD3nHhrxjf7wNUF3WuNUgbLo4pFwxr77elwalmOsUb00oVPwqZUNozVkyhbkqQKiB2Uc1OHVW2shQtj5R0IzvhJmpcwUfTLZBW-pMie5vUIp8tsR8xvTa7avjHcE0siVS3xjS-yADIc-eAOMo3E3d4lhaPPmSVVBqMJcjfX57BhePDi2x1cc3yN8tjnkquW6Wgs5QYEZjDkCsU2OE6YO5h32EJv7idxjCuyx7IQyMJpxyQJjftGpYaHpY29rppPf9deP1B7T4ivWYiN00PqEcyaLXluowdqNectiYS40waaAn0K9e44B9kVu_sF035vP7fV_3mQjE8nhLHYrBDlmrvKmWkl7oQI2kw1X0FK3aJ1yxTaoALki2APvR6rMqul-hvHSdqTvng0uAupdx2NMZZJA18Or1uQjfoY1_9H8UryUDX26t6D0qOl_vSRV2AF7IY81RZfhclhzhNX1olQV6zkHdqSfHN8fj-2Xsr2kj4f_A8DKU"
  }
}
```

Where Response Object is:

| Field           | Type     | Description                              |
| --------------- | -------- | ---------------------------------------- |
| message    | string | Response Message.                  |                    |
| data            | object   | Object containing information about user details and access token |
| user            | object   | Object containing user details |
| name            | string   | User name                            |
| email            | string   | User email                             |
| id         | integer    | User ID.                       |
| accessToken | string  | User access token                     |


-   **Possible errors**

| Error code                | Description                                     |
| ------------------------- | ----------------------------------------------- |
| 400 Bad Request           | Required fields were invalid, validation failed |
| 500 Internal Server Error | Server Error                                    |
---

### Login

---

Returns json data of user details and access token.


-   **URL and Method**
    `POST http://127.0.0.1:8000/api/v1/login`

-   **Request**

```
POST http://127.0.0.1:8000/api/v1/login
Content-Type: application/json

{
  "email": "johndoe@gmail.com",
  "password": "secret"
}
```

With the following fields:

| Parameter       | Required? | Description              |
| --------------- | --------- | ------------------------ |
| email           | required  | User email             |
| password        | required  | User Password.            |


-   **Success Response**

```
Status 200 OK
Content-Type: application/json; charset=utf-8

{
  "message": "Success",
  "data": {
    "user": {
      "name": "John Doe",
      "email": "johndoe@gmail.com",
      "id": 3
    },
    "accessToken": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIzIiwianRpIjoiMjg4YWFmN2Y4YzVlODBhZWQwNjcwNjA0NjExODYyN2YyYjE0MmVmOWRiMjNlNWU2YTE2NzdjNDI0Mzg1NzQ0MmJkNzQ2NGIzZWJhMTg0ZDIiLCJpYXQiOjE2MjYyODI3MDAsIm5iZiI6MTYyNjI4MjcwMCwiZXhwIjoxNjU3ODE4NzAwLCJzdWIiOiIzIiwic2NvcGVzIjpbXX0.SFxXYzPMQBuGyyjHAss49KlU4tawxk45kXywdT_uHbXNfyGKHXJ9LHNRDRn2nk5AtUDRtYmxrIyORmbfdjQHQRahHDc_KT-YpSXiLK39aDg5BBoAJ3mo0uhlIfW1ZjpDp0w0odv4c0WAcBClooqJmkPa5-lYMrTFULqWiU65x15guAS6ux7YXoOTsqnD3nHhrxjf7wNUF3WuNUgbLo4pFwxr77elwalmOsUb00oVPwqZUNozVkyhbkqQKiB2Uc1OHVW2shQtj5R0IzvhJmpcwUfTLZBW-pMie5vUIp8tsR8xvTa7avjHcE0siVS3xjS-yADIc-eAOMo3E3d4lhaPPmSVVBqMJcjfX57BhePDi2x1cc3yN8tjnkquW6Wgs5QYEZjDkCsU2OE6YO5h32EJv7idxjCuyx7IQyMJpxyQJjftGpYaHpY29rppPf9deP1B7T4ivWYiN00PqEcyaLXluowdqNectiYS40waaAn0K9e44B9kVu_sF035vP7fV_3mQjE8nhLHYrBDlmrvKmWkl7oQI2kw1X0FK3aJ1yxTaoALki2APvR6rMqul-hvHSdqTvng0uAupdx2NMZZJA18Or1uQjfoY1_9H8UryUDX26t6D0qOl_vSRV2AF7IY81RZfhclhzhNX1olQV6zkHdqSfHN8fj-2Xsr2kj4f_A8DKU"
  }
}
```

Where Response Object is:

| Field           | Type     | Description                              |
| --------------- | -------- | ---------------------------------------- |
| message    | string | Response Message.                  |                    |
| data            | object   | Object containing information about user details and access token |
| user            | object   | Object containing user details |
| name            | string   | User name                            |
| email            | string   | User email                             |
| id         | integer    | User ID.                       |
| accessToken | string  | User access token                     |


-   **Possible errors**

| Error code                | Description                                     |
| ------------------------- | ----------------------------------------------- |
| 400 Bad Request           | Required fields were invalid, validation failed |
| 500 Internal Server Error | Server Error                                    |
---

### Verify / Resolve Account Number

---

Returns json data of information of valid account number and back code to the application.


-   **URL and Method**
    `POST http://127.0.0.1:8000/api/v1/verify`

- **Bank 3-digit Code**
1. Access Bank - 044 
2. Afribank - 014  
3. Citibank - 023
4. Diamond Bank - 063 
5. Ecobank - 050 
6. Equitorial Trust Bank - 040 
7. First Bank - 011 
8. FCMB - 214 
9. Fidelity Bank - 070  
10. Finbank - 085 
11. Guaranty Trust Bank - 058 
12. Intercontinental Bank - 069 
13. Oceanic Bank - 056 
14. BankPhb - 082 
15. Skye Bank - 076 
16. SpringBank - 084 
17. StanbicIBTC - 221
18. Standard Chartered Bank - 068 
19. Sterling Bank - 232 
20. United Bank for Africa - 033 
21. Union Bank - 032 
22. Wema bank - 035 
23. Zenith Bank - 057 
24. Unity bank - 215




-   **Request**

```
POST http://127.0.0.1:8000/api/v1/login
Content-Type: application/json
Authorization: Bearer ACCESS_TOKEN

{
  "account_number": "2111333996",
  "bank_code": "057"
}
```

With the following fields:

| Parameter       | Required? | Description              |
| --------------- | --------- | ------------------------ |
| account_number           | required  | Recipient account number            |
| bank_code        | required  | Recipient Bank Code.            |


-   **Success Response**

```
Status 200 OK
Content-Type: application/json; charset=utf-8

{
  "status": "Success",
  "message": "Account number resolved",
  "data": {
    "account_number": "2111333996",
    "account_name": "OLUWASEGUN MOSES AJAYI",
    "bank_id": 21
  }
}
```

Where Response Object is:

| Field           | Type     | Description                              |
| --------------- | -------- | ---------------------------------------- |
| status    | string | Response Status Message.                  |
| message    | string | Response Message.                  |                    |
| data            | object   | Object containing information about user account details |
| account_number            | string   | Recipient account name                            |
| account_name            | string   | Recipient account number                             |
| bank_id       | integer    | Recipient Bank ID                       |

-   **Wrong Bank Code Response**

```
Status 200 OK
Content-Type: application/json; charset=utf-8

{
  "status": false,
  "message": "Could not resolve account name. Check parameters or try again."
}
```

-   **Wrong Account Number Response**

```
Status 200 OK
Content-Type: application/json; charset=utf-8

{
  "status": false,
  "message": "Could not resolve account name. Check parameters or try again."
}
```

-   **Possible errors**

| Error code                | Description                                     |
| ------------------------- | ----------------------------------------------- |
| 400 Bad Request           | Required fields were invalid, validation failed |
| 500 Internal Server Error | Server Error                                    |
---

### Transfer Funds

---

Returns json data of information of transaction response.

-   **URL and Method**
    `POST http://127.0.0.1:8000/api/v1/transfer`

- **Bank 3-digit Code**
1. Access Bank - 044 
2. Afribank - 014  
3. Citibank - 023
4. Diamond Bank - 063 
5. Ecobank - 050 
6. Equitorial Trust Bank - 040 
7. First Bank - 011 
8. FCMB - 214 
9. Fidelity Bank - 070  
10. Finbank - 085 
11. Guaranty Trust Bank - 058 
12. Intercontinental Bank - 069 
13. Oceanic Bank - 056 
14. BankPhb - 082 
15. Skye Bank - 076 
16. SpringBank - 084 
17. StanbicIBTC - 221
18. Standard Chartered Bank - 068 
19. Sterling Bank - 232 
20. United Bank for Africa - 033 
21. Union Bank - 032 
22. Wema bank - 035 
23. Zenith Bank - 057 
24. Unity bank - 215




-   **Request**

```
POST http://127.0.0.1:8000/api/v1/login
Content-Type: application/json
Authorization: Bearer ACCESS_TOKEN

{
  "account_number": "2111333996",
  "bank_code": "057",
  "amount": "1500",
  "reason": "Flex"
}
```

With the following fields:

| Parameter       | Required? | Description              |
| --------------- | --------- | ------------------------ |
| account_number           | required  | Recipient account number            |
| bank_code        | required  | Recipient Bank Code.            |
| amount        | required  | Amount to transfer.            |
| reason        | required  | Reason for transfer.            |


-   **Success Response**

```
Status 200 OK
Content-Type: application/json; charset=utf-8

{
  "status": "Success",
  "message": "You Successfully transferred 1500 to OLUWASEGUN MOSES AJAYI for Flex"
}
```

Where Response Object is:

| Field           | Type     | Description                              |
| --------------- | -------- | ---------------------------------------- |
| status    | string | Response Status Message.                  |
| message    | string | Response Message.                  |                    

-   **Wrong Recipient Account Details Response**

```
Status 200 OK
Content-Type: application/json; charset=utf-8

{
  "status": false,
  "message": "Could not resolve account name. Check parameters or try again."
}
```


-   **Possible errors**

| Error code                | Description                                     |
| ------------------------- | ----------------------------------------------- |
| 400 Bad Request           | Required fields were invalid, validation failed |
| 500 Internal Server Error | Server Error                                    |
---

### Search Transaction History by Account Number 

---

Returns json data of transaction history to the application.

-   **URL and Method**
    `GET http://127.0.0.1:8000/api/v1/history?accountNumber=2111333996`

-   **Query Params**
    `?accountNumber=2111333996`

    **Required:**

`Recipient Account Number {accountNumber} in query paramaeter`

`Authorization: Bearer ACCESS_TOKEN`

-   **Success Response**

```
Status 200 OK
Content-Type: application/json; charset=utf-8

{
  "status": "success",
  "data": [
    {
      "account_name": "OLUWASEGUN MOSES AJAYI",
      "account_number": "2111333996",
      "amount": "1500",
      "reason": "Flex",
      "status": "Successful",
      "created_at": "2021-07-15T03:43:02.000000Z"
    },
    {
      "account_name": "OLUWASEGUN MOSES AJAYI",
      "account_number": "2111333996",
      "amount": "1500",
      "reason": "Flex",
      "status": "Successful",
      "created_at": "2021-07-15T03:47:12.000000Z"
    }
  ]
}
```

Where Response Object is:

| Field           | Type     | Description                              |
| --------------- | -------- | ---------------------------------------- |
| status          | string   | Status of request                        |
| data            | object   | Object containing information about transaction(s) |
| account_name             | string   | Recipient account name               |
| account_number            | string   | Recipient account number                             |
| amount            | string   | Amount transferred                             |
| reason        | string   | Reason for transfer.                       |
| status | string | Status of transaction                    |
| created_at       | date   | Date transaction took place                           |


-   **Request Successful but Empty Response** - Simply means there's no history with account number given.

```
Status 200 OK
{
  "status": "success",
  "message": "No history found"
}
```

---

### List all Transaction History 

---

Returns json data of all transaction history to the application.

-   **URL and Method**
    `GET http://127.0.0.1:8000/api/v1/history`

    **Required:**

`Authorization: Bearer ACCESS_TOKEN`

-   **Success Response**

```
Status 200 OK
Content-Type: application/json; charset=utf-8

{
  "status": "success",
  "data": [
    {
      "account_name": "OLUWASEGUN MOSES AJAYI",
      "account_number": "2111333996",
      "amount": "1500",
      "reason": "Flex",
      "status": "Successful",
      "created_at": "2021-07-15T03:43:02.000000Z"
    },
    {
      "account_name": "OLUWASEGUN MOSES AJAYI",
      "account_number": "2111333996",
      "amount": "1500",
      "reason": "Flex",
      "status": "Successful",
      "created_at": "2021-07-15T03:47:12.000000Z"
    }
  ]
}
```

Where Response Object is:

| Field           | Type     | Description                              |
| --------------- | -------- | ---------------------------------------- |
| status          | string   | Status of request                        |
| data            | object   | Object containing information about transaction(s) |
| account_name             | string   | Recipient account name               |
| account_number            | string   | Recipient account number                             |
| amount            | string   | Amount transferred                             |
| reason        | string   | Reason for transfer.                       |
| status | string | Status of transaction                    |
| created_at       | date   | Date transaction took place                           |


-   **Request Successful but Empty Response** - Simply means there's no transaction as been made yet.

```
Status 200 OK
{
  "status": "success",
  "message": "You haven't made a transaction yet."
}
```
---

