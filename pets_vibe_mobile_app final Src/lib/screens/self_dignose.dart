import 'dart:convert';
import 'dart:io';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:image_picker/image_picker.dart';
import 'package:lottie/lottie.dart';
import 'package:pets_vibe/widget/app_drawer.dart';
import 'package:pets_vibe/widget/bottomsheetwidget.dart';
// import 'disease_bottom_sheet_widget.dart'; // Import the bottom sheet widget

class PetDiseaseDetectorScreen extends StatefulWidget {
  const PetDiseaseDetectorScreen({super.key});

  @override
  _PetDiseaseDetectorScreenState createState() =>
      _PetDiseaseDetectorScreenState();
}

class _PetDiseaseDetectorScreenState extends State<PetDiseaseDetectorScreen> {
  File? _image;
  String _result = "Upload an image to detect disease.";
  bool _isLoading = false;
  String? detectedDisease;
  bool _isDisclaimerAccepted = false;

  @override
  void initState() {
    super.initState();
    _showDisclaimerDialog();
  }

  Future<void> _showDisclaimerDialog() async {
    await Future.delayed(const Duration(milliseconds: 500));
    showDialog(
      context: context,
      barrierDismissible: false,
      builder:
          (context) => AlertDialog(
            backgroundColor: const Color.fromARGB(255, 244, 224, 158),
            title: const Text("⚠️ Important Disclaimer"),
            titleTextStyle: const TextStyle(
              fontWeight: FontWeight.bold,
              color: Colors.black,
              fontSize: 20,
            ),
            content: const Text(
              "• This AI model is not 100% accurate.\n"
              "• It should not replace a veterinarian's advice.\n"
              "• If symptoms persist, consult a pet doctor immediately.\n"
              "• The app provides suggestions but may not detect rare diseases.",
              style: TextStyle(color: Colors.black, fontSize: 16),
            ),
            actions: [
              ElevatedButton(
                onPressed: () {
                  setState(() => _isDisclaimerAccepted = true);
                  Navigator.pop(context);
                },
                style: ElevatedButton.styleFrom(
                  padding: const EdgeInsets.symmetric(
                    horizontal: 60,
                    vertical: 12,
                  ),
                ),
                child: const Text("Agree & Continue"),
              ),
            ],
          ),
    );
  }

  Future<void> _pickImage(ImageSource source) async {
    if (_isLoading || !_isDisclaimerAccepted) return;

    setState(() => _isLoading = true);
    try {
      final pickedFile = await ImagePicker().pickImage(source: source);
      if (pickedFile != null) {
        setState(() => _image = File(pickedFile.path));
        await _uploadImage(_image!);
      }
    } catch (e) {
      print("Error picking image: $e");
    } finally {
      setState(() => _isLoading = false);
    }
  }

  Future<void> _uploadImage(File imageFile) async {
    const String apiKey = "RFTdfyI5GFkkEfqoCEXN";
    const String modelId = "dogskin-gabungan3x/1";
    final String url = "https://detect.roboflow.com/$modelId?api_key=$apiKey";

    var request = http.MultipartRequest("POST", Uri.parse(url));
    request.files.add(
      await http.MultipartFile.fromPath("file", imageFile.path),
    );

    try {
      var response = await request.send();
      var responseData = await response.stream.bytesToString();
      var jsonResponse = json.decode(responseData);

      if (jsonResponse.containsKey("predictions") &&
          jsonResponse["predictions"].isNotEmpty) {
        var prediction = jsonResponse["predictions"][0];
        setState(() {
          detectedDisease = prediction['class'];
          _result =
              "Detected: ${prediction['class']} \nConfidence: ${(prediction['confidence'] * 100).toStringAsFixed(2)}%";
        });
      } else {
        setState(() {
          detectedDisease = null;
          _result =
              "Sorry, we are unable to detect it. Please visit a veterinarian.";
        });
      }
    } catch (e) {
      print("Error uploading image: $e");
      setState(() => _result = "Error detecting disease. Try again.");
    }
  }

  Future<void> _triggerbottomsheet() async {
    if (detectedDisease == null) return;
    DiseaseBottomSheetWidget.show(context, detectedDisease!);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      drawer: AppDrawer(),
      appBar: AppBar(
        title: const Text("Pet Disease Detector"),
        centerTitle: true,
        elevation: 2,
      ),
      body: SizedBox(
        height: double.infinity,
        child: Stack(
          children: [
            Container(
              decoration: BoxDecoration(
                gradient: LinearGradient(
                  begin: Alignment.topLeft,
                  end: Alignment.bottomRight,
                  colors: [
                    const Color.fromARGB(255, 80, 165, 235),
                    const Color.fromARGB(255, 225, 138, 241),
                  ],
                ),
              ),
              child:
                  _isDisclaimerAccepted
                      ? SingleChildScrollView(
                        padding: const EdgeInsets.all(16.0),
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.center,
                          children: [
                            GestureDetector(
                              onTap: () => _pickImage(ImageSource.gallery),
                              child: Container(
                                height: 250,
                                width: double.infinity,
                                decoration: BoxDecoration(
                                  borderRadius: BorderRadius.circular(15),
                                  color: Colors.grey[200],
                                  boxShadow: [
                                    BoxShadow(
                                      color: Colors.black12,
                                      blurRadius: 5,
                                      spreadRadius: 2,
                                    ),
                                  ],
                                ),
                                child:
                                    _isLoading
                                        ? Center(
                                          child: Lottie.asset(
                                            'assets/uploading.json',
                                            width: 100,
                                            height: 100,
                                          ),
                                        )
                                        : _image != null
                                        ? ClipRRect(
                                          borderRadius: BorderRadius.circular(
                                            15,
                                          ),
                                          child: Image.file(
                                            _image!,
                                            fit: BoxFit.cover,
                                            width: double.infinity,
                                          ),
                                        )
                                        : Column(
                                          mainAxisAlignment:
                                              MainAxisAlignment.center,
                                          children: [
                                            Icon(
                                              Icons.cloud_upload,
                                              size: 50,
                                              color: Colors.grey,
                                            ),
                                            const SizedBox(height: 10),
                                            const Text(
                                              "Tap to upload image",
                                              style: TextStyle(
                                                fontSize: 16,
                                                color: Colors.black54,
                                              ),
                                            ),
                                          ],
                                        ),
                              ),
                            ),
                            const SizedBox(height: 20),
                            _isLoading
                                ? const CircularProgressIndicator()
                                : Card(
                                  elevation: 2,
                                  shape: RoundedRectangleBorder(
                                    borderRadius: BorderRadius.circular(10),
                                  ),
                                  child: Padding(
                                    padding: const EdgeInsets.all(12.0),
                                    child: Text(
                                      _result,
                                      style: const TextStyle(
                                        fontSize: 16,
                                        fontWeight: FontWeight.w500,
                                      ),
                                      textAlign: TextAlign.center,
                                    ),
                                  ),
                                ),
                            const SizedBox(height: 20),
                            ElevatedButton(
                              onPressed:
                                  detectedDisease != null
                                      ? _triggerbottomsheet
                                      : null,
                              child: const Text("Find Remedies"),
                            ),
                          ],
                        ),
                      )
                      : const Center(child: CircularProgressIndicator()),
            ),
            Align(
              alignment: Alignment.bottomRight,
              child: Padding(
                padding: const EdgeInsets.all(16.0),
                child: FloatingActionButton(
                  onPressed: () => _pickImage(ImageSource.camera),
                  backgroundColor: const Color.fromARGB(255, 0, 55, 255),
                  child: const Icon(Icons.camera_alt, color: Colors.white),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
