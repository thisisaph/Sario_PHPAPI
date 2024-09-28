<?php

// Input all functions in an interface
interface QuizInterface {
    public function getAll();
    public function getSingleID($id);
    public function insert($user);
    public function update($user);
    public function delete($id);
}


class allFunctions implements QuizInterface {
    protected $pdo, $glb;
    protected $table_name = "users";

    public function __construct(\PDO $pdo, GlobalMethods $glb) {
        $this->pdo = $pdo;
        $this->glb = $glb;
    }

    // We can create routines here in MySQL
    // Get all User Details
    public function getAll()
    {
        $sql = "SELECT * FROM " . $this->table_name;
        try {
            $stmt = $this->pdo->prepare($sql);

            if ($stmt->execute()) {
                $user = $stmt->fetchAll();
                if ($stmt->rowCount() >= 1) {
                    return $this->glb->responsePayload($user, "success", "Successfully pulled all data!", 200);
                } else {
                    return $this->glb->responsePayload(null, "failed", "No data exisiting.", 404);
                }
            }
        } catch(\PDOException $e) {
            echo $e->getMessage();
        }
    }

    // Get a Single User ID
    public function getSingleID($id)
    {
        $sql = "SELECT * FROM " . $this->table_name . " WHERE id = ?";
        try {
            $stmt = $this->pdo->prepare($sql);

            if ($stmt->execute([$id])) {
                $user = $stmt->fetch();
                if ($user) {
                    return $this->glb->responsePayload($user, "success", "User found!", 200);
                } else {
                    return $this->glb->responsePayload(null, "failed", "Record not found.", 404);
                }
            }
        } catch(\PDOException $e) {
            echo $e->getMessage();
        }
    }

    // Insert User
    public function insert($user)
    {
        $sql = "INSERT INTO " . $this->table_name . "(firstname,lastname,is_admin) VALUES(?,?,?)";
        try {
            $stmt = $this->pdo->prepare($sql);

            if ($stmt->execute([$user->firstname, $user->lastname, $user->is_admin])) {
                return $this->glb->responsePayload(null, "success", "User successfully inserted!", 200);
            } else {
                return $this->glb->responsePayload(null, "failed", "Failed to insert user.", 400);
            }
        } catch(\PDOException $e) {
            echo $e->getMessage();
        }
    }

    // Update User
    public function update($user)
    {
        $sql = "UPDATE " . $this->table_name . " SET firstname = ?, lastname = ?, is_admin = ? WHERE id = ?";
        try {
            $stmt = $this->pdo->prepare($sql);
    
            if ($stmt->execute([$user->firstname, $user->lastname, $user->is_admin, $user->id])) {
                return $this->glb->responsePayload(null, "success", "User data updated!", 200);
            } else {
                return $this->glb->responsePayload(null, "failed", "Failed to update user.", 400);
            }
        } catch(\PDOException $e) {
            echo $e->getMessage();
        }
    }

     // Delete User
    public function delete($id)
    {
        $sql = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        try {
            $stmt = $this->pdo->prepare($sql);

            $stmt->execute([$id]);
                return $this->glb->responsePayload(null, "success", "User deleted!", 200);
        } catch(\PDOException $e) {
                return $this->glb->responsePayload(null, "error", $e->getMessage(), 500);
        }
    }
}