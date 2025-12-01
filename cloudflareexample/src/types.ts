import { DateTime, Str } from "chanfana";
import type { Context } from "hono";
import { z } from "zod";

export interface AppContextVariables {
	currentUser?: UserAuth;
}

export type AppContext = Context<{ 
	Bindings: Env;
	Variables: AppContextVariables; 
}>;

export const Task = z.object({
	name: Str({ example: "lorem" }),
	slug: Str(),
	description: Str({ required: false }),
	completed: z.boolean().default(false),
	due_date: DateTime(),
	
});

//define the role enum
const UserRole = z.enum(["user","admin"]);
type UserRole = z.infer< typeof UserRole>;

//Define user schema
export const User = z.object({
	id: z.number().int().positive({
		message: "ID must be a positive integer",
	}),
	username: z
		.string()
		.min(3,{message:"Username must be at least 3 character long"})
		.max(50,{message:"Username must be at most 50 character long"}),
	email: z.string().email({message: "Invalid email address"}),
	role: UserRole.default("user"),
	passwordHash: z.string(),
	password: z
		.string()
		.min(8,{message:"Password must be at least 8 character long"})
		.optional()
});

//Response schema without password for security
export const UserResponse = User.omit({password: true});

//Request Schema for user creation - without ID as it will be created
export const CreateUserSchema = User.omit({id: true});

export {UserRole};

//User type for update requests (all fields optional except for ID)
export const UpdateUserSchema = User.partial().required({id:true});
export type UpdateUserSchema = z.infer<typeof UpdateUserSchema>;

//login request type
export const LoginSchema = z.object({
	email: z.string().email(),
	password: z.string(),
});

//Auth user type for JWT payload

export const UserAuthSchema = User.pick({
	id: true,
	username: true,
	email: true,
	role: true,
});
export type UserAuth = z.infer<typeof UserAuthSchema>;