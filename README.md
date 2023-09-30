## Influencers App Backend

This README document outlines the development of the backend module for the Influencers App. The backend is responsible for managing influencer and client data, as well as facilitating communication between them through invitations and reporting.

### Functionalities:

- Influencers and Clients Onboarding: The App allows potential customers to add influencers and clients to the platform.
- Client Dashboard: Clients can access a dashboard to generate event reports and contact influencers.
- Notification System: The backend sends notifications to influencers for public invitations.
- Filtered Search for Influencers: Clients can filter and search for specific influencers based on gender, age, and Instagram follower count.
- Private Invitations: Clients can send private invitations to specific influencers for particular events, and influencers will be notified via email.
- Credit-based Packages: Different influencer group packages are offered, providing clients with credits to use as needed.
- Influencer Reputation System: Clients have the ability to penalize influencers they consider unsuitable for their establishment. A global penalty would result in the influencer's logical deletion.

### User Endpoints:
1. **List Users**
   - Method: GET
   - Endpoint: `/users`
   - Description: This endpoint allows retrieving a list of all users.
   - Access: Public

2. **Create User**
   - Method: POST
   - Endpoint: `/user`
   - Description: This endpoint enables the creation of a new user.
   - Access: Public

3. **Soft Delete User**
   - Method: DELETE
   - Endpoint: `/user/{id}`
   - Description: This endpoint soft deletes the user with the given ID.
   - Access: Public

4. **Update User**
   - Method: PUT
   - Endpoint: `/user/{id}`
   - Description: This endpoint allows updating user information by providing the user ID.
   - Access: Public

## Admin-Only User Endpoints:

5. **List User Roles**
   - Method: GET
   - Endpoint: `/users/roles`
   - Description: This endpoint provides a list of user roles and is accessible only to users with the "SUPER_ADMIN" role.
   - Access: Admin (requires authentication and "SUPER_ADMIN" role)

6. **Assign Role to User**
   - Method: POST
   - Endpoint: `/user/{id}/assign-role`
   - Description: This endpoint allows assigning a role to a specific user by providing the user ID and role name.
   - Access: Admin (requires authentication and "SUPER_ADMIN" role)

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