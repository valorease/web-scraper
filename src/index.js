import { app } from "./app";

(async () => {
  while (true) {
    await app();
  }
});
