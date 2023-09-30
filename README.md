## Pets App Backend

This README document outlines the development of the backend module for the Pets App. The backend is responsible for managing pet and client data, as well as facilitating communication between them through invitations and reporting.

### User Endpoints:
1. **List Users**
   - Method: GET
   - Endpoint: `/users`
   - Description: This endpoint allows retrieving a list of all users.
   - Access: Public

2. **Create User**
   - Method: POST
   - Endpoint: `/users`
   - Description: This endpoint enables the creation of a new user.
   - Access: Public

3. **Soft Delete User**
   - Method: DELETE
   - Endpoint: `/users/{id}`
   - Description: This endpoint soft deletes the user with the given ID.
   - Access: Public

4. **Update User**
   - Method: PUT
   - Endpoint: `/users/{id}`
   - Description: This endpoint allows updating user information by providing the user ID.
   - Access: Public

## Authentication Endpoints:

7. **Get Authentication Token**
   - Method: POST
   - Endpoint: `/auth/token`
   - Description: This endpoint allows users to obtain an authentication token.
   - Access: Public

8. **Logout**
   - Method: GET
   - Endpoint: `/auth/logout`
   - Description: This endpoint logs out an authenticated user by invalidating the authentication token.
   - Access: Authenticated users only