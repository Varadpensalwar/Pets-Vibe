import 'dart:convert';
import 'dart:io';
import 'package:flutter/material.dart';
import 'package:image_picker/image_picker.dart';
import 'package:http/http.dart' as http;
import 'package:lottie/lottie.dart';
import 'package:pets_vibe/widget/app_drawer.dart';

class MoodDetectionScreen extends StatefulWidget {
  const MoodDetectionScreen({super.key});

  @override
  _MoodDetectionScreenState createState() => _MoodDetectionScreenState();
}

class _MoodDetectionScreenState extends State<MoodDetectionScreen> {
  File? _image;
  String _mood = "";
  bool _isLoading = false;

  final String apiKey = "RFTdfyI5GFkkEfqoCEXN";
  final String modelId = "dog-emotion-x091f/1";

  Future<void> _pickImage({bool fromCamera = false}) async {
    final pickedFile = await ImagePicker().pickImage(
      source: fromCamera ? ImageSource.camera : ImageSource.gallery,
    );
    if (pickedFile != null) {
      setState(() {
        _image = File(pickedFile.path);
        _mood = "";
      });
      _detectMood();
    }
  }

  Future<void> _detectMood() async {
    if (_image == null) return;
    setState(() => _isLoading = true);

    try {
      var request = http.MultipartRequest(
        'POST',
        Uri.parse('https://detect.roboflow.com/$modelId?api_key=$apiKey'),
      );

      request.files.add(
        await http.MultipartFile.fromPath('file', _image!.path),
      );

      var response = await request.send();
      var responseData = await response.stream.bytesToString();
      var jsonResponse = json.decode(responseData);

      setState(() {
        _mood = jsonResponse['predictions'][0]['class'] ?? "Unknown";
        _isLoading = false;
      });
    } catch (e) {
      setState(() {
        _mood = "Error detecting mood";
        _isLoading = false;
      });
    }
  }

  Widget _getMoodAnimation() {
    switch (_mood.toLowerCase()) {
      case 'happy':
        return Lottie.asset('assets/happy.json', height: 150);
      case 'sad':
        return Lottie.asset('assets/sad.json', height: 150);
      case 'angry':
        return Lottie.asset('assets/angry.json', height: 150);
      case 'relaxed':
        return Lottie.asset('assets/relaxed.json', height: 150);
      default:
        return Container();
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      drawer: AppDrawer(),
      appBar: AppBar(
        title: const Text("Pet Mood Detection"),
        centerTitle: true,
        backgroundColor: Colors.grey[100],
      ),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Center(
          child: SingleChildScrollView(
            child: Column(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                _image == null
                    ? Column(
                      children: [
                        Icon(
                          Icons.pets,
                          size: 100,
                          color: const Color.fromARGB(255, 4, 137, 247),
                        ),
                        const SizedBox(height: 10),
                        const Text(
                          "Select or capture an image of your pet",
                          style: TextStyle(
                            fontSize: 18,
                            fontWeight: FontWeight.w500,
                            color: Colors.black54,
                          ),
                          textAlign: TextAlign.center,
                        ),
                      ],
                    )
                    : ClipRRect(
                      borderRadius: BorderRadius.circular(16),
                      child: Image.file(
                        _image!,
                        height: 200,
                        fit: BoxFit.cover,
                      ),
                    ),
                const SizedBox(height: 30),
                _isLoading
                    ? const CircularProgressIndicator()
                    : _mood.isNotEmpty
                    ? Column(
                      children: [
                        Text(
                          "Detected Mood:",
                          style: TextStyle(
                            fontSize: 20,
                            fontWeight: FontWeight.w600,
                            color: Colors.deepPurple,
                          ),
                        ),
                        const SizedBox(height: 8),
                        Text(
                          _mood,
                          style: const TextStyle(
                            fontSize: 28,
                            fontWeight: FontWeight.bold,
                            color: Colors.black87,
                          ),
                        ),
                        const SizedBox(height: 20),
                        _getMoodAnimation(),
                      ],
                    )
                    : Container(),
                const SizedBox(height: 80), // spacing for floating button
              ],
            ),
          ),
        ),
      ),
      floatingActionButton: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          FloatingActionButton.extended(
            onPressed: () => _pickImage(fromCamera: true),
            label: const Text("Camera"),
            icon: const Icon(Icons.camera_alt),
            backgroundColor: const Color.fromARGB(255, 242, 242, 244),
          ),
          const SizedBox(height: 10),
          FloatingActionButton.extended(
            onPressed: () => _pickImage(fromCamera: false),
            label: const Text("Gallery"),
            icon: const Icon(Icons.photo_library),
            backgroundColor: const Color.fromARGB(255, 255, 254, 255),
          ),
        ],
      ),
    );
  }
}
