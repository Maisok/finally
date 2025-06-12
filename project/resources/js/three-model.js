import * as THREE from 'three';
import { OrbitControls } from 'three/examples/jsm/controls/OrbitControls.js';
import { GLTFLoader } from 'three/examples/jsm/loaders/GLTFLoader.js';

window.init3DModel = function () {
    const canvas = document.getElementById('modelCanvas');
    if (!canvas) return;

    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(
        75,
        canvas.clientWidth / canvas.clientHeight,
        0.1,
        1000
    );
    const renderer = new THREE.WebGLRenderer({
        canvas: canvas,
        antialias: true,
        alpha: true
    });

    renderer.setSize(canvas.clientWidth, canvas.clientHeight);
    renderer.setClearColor(0xffffff, 1);

    // Освещение
    const ambientLight = new THREE.AmbientLight(0xffffff, 2);
    scene.add(ambientLight);

    const frontLight = new THREE.DirectionalLight(0xffffff, 2);
    frontLight.position.set(0, 0.5, 2);
    scene.add(frontLight);

    const backLight = new THREE.DirectionalLight(0xccddff, 1);
    backLight.position.set(0, 0.5, -2);
    scene.add(backLight);

    const leftLight = new THREE.DirectionalLight(0xffffff, 5);
    leftLight.position.set(-2, 0.5, 0);
    scene.add(leftLight);

    const rightLight = new THREE.DirectionalLight(0xffffff, 5);
    rightLight.position.set(2, 0.5, 0);
    scene.add(rightLight);

    const topLight = new THREE.DirectionalLight(0xffffff, 2);
    topLight.position.set(0, 2, 0);
    scene.add(topLight);

    // Controls
    const controls = new OrbitControls(camera, renderer.domElement);
    controls.enableDamping = true;
    controls.dampingFactor = 0.25;

    camera.position.z = 5;

    // Загрузка модели
    const loader = new GLTFLoader();

    const modelUrl = window.modelPath; // Мы передадим путь из Blade
    loader.load(
        modelUrl,
        function (gltf) {
            const model = gltf.scene;

            model.traverse(function (child) {
                if (child.isMesh) {
                    child.castShadow = true;
                    child.receiveShadow = true;
                    if (child.material) {
                        child.material.roughness = 0.2;
                        child.material.metalness = 0.9;
                    }
                }
            });

            scene.add(model);

            const box = new THREE.Box3().setFromObject(model);
            const center = box.getCenter(new THREE.Vector3());
            model.position.sub(center);
            const size = box.getSize(new THREE.Vector3());
            const maxDim = Math.max(size.x, size.y, size.z);
            const scale = 3 / maxDim;
            model.scale.set(scale, scale, scale);

            function animate() {
                requestAnimationFrame(animate);
                controls.update();
                renderer.render(scene, camera);
            }

            animate();
        },
        undefined,
        function (error) {
            console.error('Error loading model:', error);
            const modelContainer = document.getElementById('modelContainer');
            if (modelContainer) {
                modelContainer.innerHTML = `
                    <div class="text-center p-4 text-red-400">
                        Не удалось загрузить 3D модель: ${error.message}
                        <div class="mt-2">URL: ${modelUrl}</div>
                    </div>
                `;
            }
        }
    );

    // Обработка изменения размера окна
    window.addEventListener('resize', function () {
        camera.aspect = canvas.clientWidth / canvas.clientHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(canvas.clientWidth, canvas.clientHeight);
    });
};