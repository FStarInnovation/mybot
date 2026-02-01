import { fal } from "@fal-ai/client";

// Configure with provided API key
fal.config({ credentials: "b7e69878-41ec-4327-998e-ae48ee544545:433efdd20d2d3dfb2ffe6c83b0a73f7b" });

async function generateWithReference() {
  try {
    const result = await fal.subscribe("fal-ai/nano-banana-pro", {
      input: {
        prompt: "Use the provided product image as the exact visual reference for the Rimmel London lipstick, match shape, proportions, materials, colors, red band position, logo placement and surface finish precisely, no redesign; photorealistic vertical 9:16 luxury beauty fashion editorial for social media stories featuring a female model leaning against a massively oversized Rimmel London lipstick; female, early 20s, slim athletic body, light natural skin tone, dark brown hair in a high ponytail with loose front strands, soft jawline, refined nose, expressive eyes, confident thoughtful editorial gaze, relaxed pose with elbow and forearm resting on the product and fingers near lips; wardrobe: black cropped zip-up hoodie, minimal white briefs, white crew socks, black Prada loafers, clean sporty minimal fashion style; hero product: single lipstick only, closed with cap attached, realistic retail form, extreme oversized towering scale significantly larger than the model's torso, lipstick height equal to or exceeding model shoulder height; interaction: model naturally leans on the giant lipstick as if it were studio furniture; environment: studio setting with neutral warm beige backdrop and seamless floor, minimal luxury beauty campaign vibe; lighting: soft editorial studio lighting with gentle highlights on the model's face and controlled specular reflections on the lipstick, soft shadows; camera: vertical fashion editorial framing optimized for stories, subject centered in safe zone, medium-close to three-quarter crop, 50mm lens look, tack-sharp face and product edges; render style: very high photorealism with realistic skin pores, natural fabric folds and accurate cosmetic plastic reflections; restrictions: no stacks, no duplicates, no exploded views, no abstraction, no deformation, no illustration, no CGI look, no fantasy styling. brand text must remain perfectly readable, centered and undistorted at all times",
        reference_images: [
          "https://farmacityar.vtexassets.com/arquivos/ids/223985-1200-auto?v=638883790343100000&width=1200&height=auto&aspect=true"
        ],
        num_images: 1,
        output_format: "png",
        resolution: "1K"
      },
      logs: true,
      onQueueUpdate: (update) => {
        if (update.status === "IN_PROGRESS") {
          update.logs.map((log) => log.message).forEach(console.log);
        }
      },
    });

    console.log("Generated image URL:", result.data.images[0].url);
    console.log("Request ID:", result.requestId);
    return result.data;
  } catch (error) {
    console.error("Error generating image:", error);
    throw error;
  }
}

// Run the function
generateWithReference().then(() => {
  console.log("Done");
}).catch(err => {
  console.error("Failed:", err);
});
