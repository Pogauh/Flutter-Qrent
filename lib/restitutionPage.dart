import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert' as convert;

class RestitutionPage extends StatefulWidget {
  const RestitutionPage({Key? key, required this.title}) : super(key: key);

  final String title;

  @override
  State<RestitutionPage> createState() => _RestitutionPageState();
}

class _RestitutionPageState extends State<RestitutionPage> {
  TextEditingController _idController = TextEditingController();

  String? materielId;
  String adminId = "1";
  String? scannedMaterielQRCode;
  String? scannedAdminQRCode;
  String apiMateriel = "";
  String apiAdmin = "";
  int etudiantId = 1;

  bool produitFound = false;
  bool restitutionValide = false;

  Map<String, dynamic>? _produitData;
  var date = DateTime.now().add(const Duration(days: 0)).toIso8601String();

  @override
  void initState() {
    super.initState();
  }

  calcul() {
    apiMateriel = "/Qrent/public/api/materiels/$materielId";
    apiAdmin = "/Qrent/public/api/users/$adminId";
    date = formatCurrentDate();
  }

  String formatCurrentDate() {
    DateTime now = DateTime.now().toUtc();
    String formattedDateEmprunt = "${now.toIso8601String().substring(0, 23)}Z";
    return formattedDateEmprunt;
  }

  fetchProduit(String id) async {
    try {
      final response = await http.get(
        Uri.parse(
            'https://s3-4295.nuage-peda.fr/Qrent/public/api/materiels/$id'),
      );
      if (response.statusCode == 200) {
        setState(() {
          _produitData = convert.jsonDecode(response.body);
          materielId = _produitData!['id'].toString();
          produitFound = true;
          materielId = id;
          calcul();
        });
      } else {
        setState(() {
          produitFound = false;
          _produitData = null;
        });
        print('Produit non trouvé');
      }
    } catch (e) {
      print('Erreur lors de la recherche du produit: $e');
    }
  }

  Future<http.Response> validerRestitution(
      String apiMateriel, String apiAdmin) {
    return http.post(
      Uri.parse('https://s3-4295.nuage-peda.fr/Qrent/public/api/retours'),
      headers: <String, String>{'Content-Type': 'application/ld+json'},
      body: convert.jsonEncode({
        'materiel': apiMateriel,
        'user': apiAdmin,
        'dateRetour': date,
      }),
    );
  }

  Future<void> enregistrerRestitution() async {
    if (_produitData != null) {
      var response = await validerRestitution(apiMateriel, apiAdmin);

      /*print(date);
      print(apiAdmin);
      print(apiMateriel);
      print(response.body);*/

      if (response.statusCode == 201) {
        setState(() {
          restitutionValide = true;
        });
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text("Restitution validée avec succès")),
        );
      } else {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text("Erreur lors de la restitution")),
        );

        setState(() {
          restitutionValide = false;
        });
      }
    } else {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text("Aucun produit trouvé")),
      );
    }
  }

  Widget buildProduitDetails() {
    if (produitFound && _produitData != null) {
      return Column(
        children: [
          Text(
            'Produit: ${_produitData!['nom']}',
            style: const TextStyle(fontSize: 20),
          ),
          Text(
            'Description: ${_produitData!['description']}',
            style: const TextStyle(fontSize: 16),
          ),
        ],
      );
    } else if (_produitData == null) {
      return const Text(
        'Aucun produit trouvé pour cette référence',
        style: TextStyle(fontSize: 16),
      );
    } else {
      return const CircularProgressIndicator();
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        backgroundColor: Theme.of(context).colorScheme.inversePrimary,
        title: Text(
          widget.title,
          style: const TextStyle(fontSize: 30, fontWeight: FontWeight.bold),
        ),
        centerTitle: true,
      ),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            ElevatedButton(
              onPressed: () {
                // Scan QR code du matériel (logique à ajouter)
              },
              child: const Text(
                "Scanner le QR code du matériel",
                style: TextStyle(fontSize: 20),
              ),
            ),
            const SizedBox(height: 20),
            Text(
              scannedMaterielQRCode != null
                  ? "QR Code matériel scanné: $scannedMaterielQRCode"
                  : "Aucun QR Code matériel scanné",
              style: const TextStyle(fontSize: 16),
            ),
            TextField(
              controller: _idController,
              decoration: const InputDecoration(
                labelText: "ID du matériel",
                border: OutlineInputBorder(),
              ),
            ),
            const SizedBox(height: 20),
            ElevatedButton(
              onPressed: () {
                fetchProduit(_idController.text);
              },
              child: const Text(
                "Rechercher le matériel",
                style: TextStyle(fontSize: 20),
              ),
            ),
            const SizedBox(height: 40),
            buildProduitDetails(),
            const SizedBox(height: 20),
            ElevatedButton(
              onPressed: () {
                // Scan QR code de l'administrateur (logic à implémenter)
              },
              child: const Text(
                "Scanner le QR code de l'administrateur",
                style: TextStyle(fontSize: 20),
              ),
            ),
            const SizedBox(height: 20),
            Text(
              scannedAdminQRCode != null
                  ? "QR Code admin scanné: $scannedAdminQRCode"
                  : "Aucun QR Code admin scanné",
              style: const TextStyle(fontSize: 16),
            ),
            const SizedBox(height: 40),
            ElevatedButton(
              onPressed: () {
                enregistrerRestitution();
              },
              child: const Text(
                "Valider la restitution",
                style: TextStyle(fontSize: 20),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
