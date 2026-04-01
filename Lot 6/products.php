<?php
/**
 * Modèle Produit orienté objet.
 */
class Product {
    private $id;
    private $type;
    private $title;
    private $subhead;
    private $price;
    private $image;

    public function __construct($id, $type, $title, $subhead = '', $price = null, $image = null) {
        $this->id = (int) $id;
        $this->type = (string) $type;
        $this->title = (string) $title;
        $this->subhead = (string) $subhead;
        $this->price = $price !== null && $price !== '' ? (float) $price : null;
        $this->image = $this->normalizeImage($image);
    }

    private function normalizeImage($image) {
        $rawImage = isset($image) ? trim((string) $image) : '';
        if ($rawImage === '' || $rawImage === 'img/') {
            return null;
        }

        $isUrl = filter_var($rawImage, FILTER_VALIDATE_URL) !== false;
        $looksLikeImagePath = preg_match('/\.(png|jpe?g|jpg|gif|svg|webp)$/i', $rawImage) === 1;
        if ($isUrl || $looksLikeImagePath) {
            return $rawImage;
        }

        return null;
    }

    public static function fromDatabaseRow(array $row) {
        return new self(
            $row['idproduit'] ?? 0,
            $row['typeproduit'] ?? '',
            $row['nomproduit'] ?? '',
            $row['libproduit'] ?? '',
            $row['prixproduit'] ?? null,
            $row['imgproduit'] ?? null
        );
    }

    public function getId() {
        return $this->id;
    }

    public function getType() {
        return $this->type;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getSubhead() {
        return $this->subhead;
    }

    public function getPrice() {
        return $this->price;
    }

    public function getImage() {
        return $this->image;
    }

    public function toArray() {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'title' => $this->title,
            'subhead' => $this->subhead,
            'price' => $this->price,
            'image' => $this->image,
        ];
    }
}

/**
 * Repository des produits.
 */
class ProductRepository {
    private $conn;

    public function __construct($conn = null) {
        if ($conn) {
            $this->conn = $conn;
            return;
        }

        require __DIR__ . '/ConnexionBDD.php';
        $this->conn = isset($conn) ? $conn : null;
    }

    public function findAll() {
        if (!$this->conn) {
            error_log('Connexion DB non disponible.');
            return [];
        }

        $sql = 'SELECT idproduit, typeproduit, nomproduit, libproduit, prixproduit, imgproduit FROM produit ORDER BY idproduit ASC';
        $res = mysqli_query($this->conn, $sql);
        if ($res === false) {
            error_log('Erreur SQL (produit): ' . mysqli_error($this->conn));
            return [];
        }

        $products = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $products[] = Product::fromDatabaseRow($row);
        }

        mysqli_free_result($res);
        return $products;
    }

    public function findAllAsArray() {
        $products = $this->findAll();
        $result = [];
        foreach ($products as $product) {
            $result[] = $product->toArray();
        }
        return $result;
    }
}

/**
 * Fonction legacy conservée pour compatibilité.
 */
function getSampleProducts() {
    $repository = new ProductRepository();
    return $repository->findAllAsArray();
}
?>
